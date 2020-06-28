var roomVue = new Vue({
    el: '#room',
    data: {
        roomList: [],
        maxPage: 0,
        init:false,
        page: 1,
        isShowNo:false,
    },
    methods: {
        getRoomList() {
            var self = this;
            if (self.init) {
                openLoading();
                $('.room-swiper-slide').scrollTop(0)
            }
            $.ajax({
                type:"POST",
                url: getSelfRoomList,
                data:{
                    page: self.page,
                },
                headers: {
                    token: $.cookie('token'),
                },
                dataType:"json",
                success:function(data){
                    if (data.code === 1) {
                        var listNum = data.data.list.length % 3;
                        if (data.data.list.length < 3 && data.data.list.length !== 0) {
                            listNum = 3 - data.data.list.length;
                        }
                        for (var i = 0;i < listNum;i++) {
                            data.data.list.push({'isHidden':true});
                        }
                        self.roomList = data.data.list;
                        self.maxPage = data.data.maxPage;
                        if (self.maxPage === 0) {
                            self.maxPage = 1;
                            self.isShowNo = true;
                        } else {
                            self.isShowNo = false;
                        }

                        if (!self.init) {
                            self.initRoomListPage();
                            self.init = true
                        } else {
                            $('.im-room-list-page').jqPaginator('option', {
                                totalPages: self.maxPage
                            });
                        }
                        self.$nextTick(function(){
                            baseSwiper.update();
                        });
                    }
                },
                complete: function () {
                    closeLoading();
                }
            })
        },
        initRoomListPage() {
            var self = this;
            $('.im-room-list-page').jqPaginator({
                totalPages: self.maxPage,
                visiblePages: 10,
                currentPage: self.page,
                first: '<li class="first">首页</li>',
                last: '<li class="first">尾页</li>',
                page: '<li class="first">{{page}}</li>',
                onPageChange: function (num, type) {
                    if (type === 'change') {
                        self.setPage(num);
                        self.getRoomList();
                    }
                }
            });
        },
        setPage(num) {
            var self = this;
            self.page = num;
            $('.im-room-list-page').jqPaginator('option', {
                currentPage: self.page
            });
        }
    },
    created: function () {
        this.getRoomList();
    }
})



// totalPages	0	设置分页的总页数
// totalCounts	0	设置分页的总条目数
// pageSize	0	设置每一页的条目数
// 注意：要么设置totalPages，要么设置totalCounts + pageSize，否则报错；设置了totalCounts和pageSize后，会自动计算出totalPages。
// currentPage	1	设置当前的页码
// visiblePages	7	设置最多显示的页码数（例如有100也，当前第1页，则显示1 - 7页）
// disableClass	'disabled'	设置首页，上一页，下一页，末页的“禁用状态”样式
// activeClass	'active'	设置当前页码样式
// first	bootstrap风格	设置“首页”的Html结构
// prev	bootstrap风格	设置“上一页”的Html结构
// next	bootstrap风格	设置“下一页”的Html结构
// last	bootstrap风格	设置“末页”的Html结构
// page	bootstrap风格	设置页码的Html结构,其中可以使用{{page}}代表当前页，{{totalPages}}代表总页数，{{totalCounts}}代表总条目数（例如：上面的“极简风格”的Demo，就是使用了{{占位符}}，并将visiblePages设为1实现的。）
// 注意：first、prev、next、last。page只要设置一个，其余未设置的会变为空。
// wrapper	(无)	分页结构的Html包裹，例如：<div class="your class"></div>，一般不会用到
// onPageChange	(无)	回调函数，当换页时触发（包括初始化第一页的时候），会传入两个参数：
// 1、“目标页"的页码，Number类型
// 2、触发类型，可能的值：“init”（初始化），“change”（点击分页）