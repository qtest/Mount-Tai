//========================
//   功能：弹出一个盒子
//   版本：1.3
//   作者：武仝
//   日期：2012-09-21
//   更新：
//========================
//========================
//   功能：检测是否存在Maya
//========================
if (typeof(Maya) == "undefined") {
        var Maya = function() {}
}
//========================
//   功能：返回元素对象
//========================
_ = function(o) {
        return document.getElementById(o);
}
//========================
//   功能：检测是否是IE浏览器
//========================
Maya.isIE = function() {
        return navigator.userAgent.toLowerCase().indexOf("msie") > 0 ? true: false;
}
//========================
//   功能：检测是否是IE6浏览器
//========================
Maya.isIE6 = function() {
        return navigator.userAgent.toLowerCase().indexOf("msie 6") > 0 ? true: false;
}

//========================
//   功能：绑定事件
//   版本：1.0
//   参数：o : element，e : string，fun : function
//   日期：2012-09-21
//   更新：
//========================
Maya.BindEvent = function(o, e, fun) {
        if (Maya.isIE()) {
                o.attachEvent("on" + e,
                function() {
                        fun.call(o);
                });
        } else {
                o.addEventListener(e, fun, false);
        }
}
//========================
//   功能：解绑事件
//   版本：1.0
//   参数：o : element，e : string，fun : function
//   日期：2012-09-21
//   更新：
//========================
Maya.UnEvent = function(o, e, fun) {
        if (Maya.isIE()) {
                o.detachEvent("on" + e, fun);
        } else {
                o.removeEventListener(e, fun, false);
        }
}
//========================
//   功能：设置元素CSS
//   版本：1.0
//   参数：eleName : Object，eleCSS : Object
//   日期：2012-09-21
//   更新：
//========================
Maya.CssElement = function(eleName, eleCSS) {
        for (p in eleCSS) {
                eleName.style[p] = eleCSS[p];
        }
        return eleName;
}
//========================
//   功能：设置元素属性
//   版本：1.0
//   参数：eleName : Object，eleCSS : Object
//   日期：2012-09-23
//   更新：
//========================
Maya.attrElement = function(eleName, eleAttr) {
        for (p in eleAttr) {
                eleName[p] = eleAttr[p];
        }
        return eleName;
}
//========================
//   功能：获取元素CSS
//   版本：1.0
//   参数：eleName : Object，style : Object
//   日期：2012-09-21
//   更新：
//========================
Maya.CssGet = function(eleName, style) {
        return Maya.isIE() ? eleName.currentStyle: getComputedStyle(eleName);
}
//========================
//   功能：创建元素
//   版本：1.0
//   参数：win : window，eleName : String，eleProperty : Object
//   日期：2012-09-21
//   更新：
//========================
Maya.CreateElement = function(win, eleName, eleProperty) {
        var ele = win.document.createElement(eleName);
        if (arguments.length > 2) {
                for (p in eleProperty) {
                        ele[p] = eleProperty[p];
                }
        }
        return ele;
}
//========================
//   功能：弹出一个盒子
//   版本：3.0
//   参数：cfg : Object
//   日期：2012-09-21
//   更新：
//========================
Maya.Box = function(cfg) {
        var opt = {
                text: "窗口",
                skin: "default",
                win: window,
                type: "iframe",
                url: "",
                width: 350,
                height: 100,
                chtml: "",
                effect: false,
                position: "center",
                isConfirm: false,
                isAlert: false,
                inlineAuto: true,
                iframeAuto: true,
                iframeScroll: "auto",
                iframeReload: false,
                allowDrag: true,
                overlayShow: true,
                overlayAlpha: .1,
                btnMaximize: false,
                btnMinimize: false,
                btnClose: true,
                btnFold: false,
                btns: null,
                onShowBefore: null,
                onShow: null,
                onClose: null
        };
        var win;
        var tmp;
        var _self = this;
        var dragBg;
        var overlay, contain, head, wrapper, content, loading, bpanel, btns, iframe;
        var btnClose, btnMinimize, btnMaximize, btnFold;
        var winScreen = {};
        var eleParam = {};
        var containStatus = "normal";
        var containLeft, containTop;
        var posLeft, posTop;
        var saveTop, saveLeft, saveWidth, saveHeight;
        var iframeName = "iframe_" + parseInt(Math.random() * 100000000);
        var isHeadDown = false;
        this.getCfg = function() {
                return opt;
        }
        this.close = function() {
				if (opt.btnClose) {
                	Maya.UnEvent(btnClose, "click", eventBtnCloseClick);
				}
                if (iframe) {
                        if (Maya.isIE6()) {
                                content.removeChild(iframe);
                        }
                }
                var _c = win.__MayaBox;
                for (var i = 0; i < _c.length; i++) {
                        if (_c[i] === _self) {
                                _c.splice(i);
                        }
                }
                if (opt.effect) {
                        $(contain).slideUp(300,
                        function() {
                                win.document.body.removeChild(contain);
                        });
                } else {
                        win.document.body.removeChild(contain);
                }
                if (opt.overlayShow) {
                        win.document.body.removeChild(overlay);
                }
                if (typeof(opt.onClose) == "function") {
                        opt.onClose();
                }
                if(Maya.isIE()){
                	CollectGarbage();
                }
        }
        this.minimize = function() {
                getScreenInfo();
                if (opt.effect) {
                        var cp_w = contain.offsetWidth;
                        var cp_h = contain.offsetHeight;
                        var cp = Maya.CreateElement(win, "div", {
                                className: opt.skin + "_minimize_bg"
                        });
                        Maya.CssElement(cp, {
                                width: contain.offsetWidth + "px",
                                height: contain.offsetHeight + "px",
                                left: Maya.CssGet(contain).left,
                                top: parseInt(Maya.CssGet(contain).top) + winScreen.scrollTop + "px"
                        });
                        win.document.body.appendChild(cp);
                        $(cp).animate({
                                width: "0px",
                                height: "0px",
                                left: "0px",
                                opacity: 0,
                                top: winScreen.clientHeight + winScreen.scrollTop - 2 + "px"
                        },
                        500,
                        function() {
                                win.document.body.removeChild(cp);
                        });
                }
                Maya.CssElement(contain, {
                        display: "none"
                });
                if (opt.overlayShow) {
                        Maya.CssElement(overlay, {
                                display: "none"
                        });
                }
                checkBoxContain();
                var __tasker = Maya.CreateElement(win, "div", {
                        title: "还原",
                        className: opt.skin + "_minimize_tasker"
                });
                Maya.BindEvent(__tasker, "click",
                function() {
                        if (opt.effect) {
                                $(contain).slideDown(100);
                                $(__tasker).hide(100,
                                function() {
                                        $(this).remove();
                                });
                        } else {
                                Maya.CssElement(contain, {
                                        display: "block"
                                });
                                win.__boxContain.removeChild(this);
                        }
                        if (opt.overlayShow) {
                                Maya.CssElement(overlay, {
                                        display: "block"
                                });
                        }
                });
                __tasker.innerHTML = opt.text;
                win.__boxContain.appendChild(__tasker);
        }
        this.maximize = function() {
                getScreenInfo();
                getScreenInfo();
                if (containStatus == "minimized" || containStatus == "normal") {
                        containStatus = "maximized";
                        saveTop = parseInt(Maya.CssGet(contain).top);
                        saveLeft = parseInt(Maya.CssGet(contain).left);
                        if (Maya.CssGet(wrapper).display != "none") {
                                saveWidth = contain.offsetWidth;
                                saveHeight = content.offsetHeight;
                        }
                        opt.width = winScreen.clientWidth;
                        opt.height = winScreen.clientHeight;

                        var l = winScreen.scrollLeft,
                        t = winScreen.scrollTop;
                        w = opt.width,
                        h = opt.height - eleParam.boxBorHeight - eleParam.headHeight - eleParam.wrapperBorHeight - eleParam.btnsHeight;
                } else {
                        containStatus = "minimized";
                        var l = saveLeft,
                        t = saveTop;
                        w = saveWidth,
                        h = saveHeight;
                }
                if (opt.effect) {
                        $(contain).animate({
                                left: l + "px",
                                top: t + "px",
                                width: w + "px"
                        },
                        300);
                        $(content).animate({
                                height: h + "px"
                        },
                        300);
                } else {
                        Maya.CssElement(contain, {
                                left: l + "px",
                                top: t + "px",
                                width: w + "px"
                        });
                        Maya.CssElement(content, {
                                height: h + "px"
                        })
                }

        }
        this.fold = function() {
                if (opt.effect) {
                        $(wrapper).slideToggle(200, "linear");
                } else {
                        var s = Maya.CssGet(wrapper).display;
                        if (s == "" || s == "block") {
                                var d = "none";
                        } else {
                                var d = "block";
                        }
                        Maya.CssElement(wrapper, {
                                display: d
                        });
                }
        }
        this.getContain = function() {
                return contain;
        }
        this.getIframe = function() {
                return win.frames[iframeName];
        }
        this.setIndex = function(o) {
                var _c = win.__MayaBox;
                for (var i = 0; i < _c.length; i++) {
                        if (Maya.CssGet(_c[i].getContain()).zIndex != 99999) {
                                Maya.CssElement(_c[i].getContain(), {
                                        zIndex: 99999
                                });
                        }
                }
                Maya.CssElement(o, {
                        zIndex: 100000
                });
        }
        var getScreenInfo = function() {
                winScreen.height = Math.max(win.document.documentElement.clientHeight, win.document.documentElement.scrollHeight);
                winScreen.width = Math.max(win.document.documentElement.clientWidth, win.document.documentElement.scrollWidth);
                winScreen.clientWidth = win.document.documentElement.clientWidth;
                winScreen.clientHeight = win.document.documentElement.clientHeight;
                winScreen.scrollLeft = Math.max(win.document.documentElement.scrollLeft, win.document.body.scrollLeft);
                winScreen.scrollTop = Math.max(win.document.documentElement.scrollTop, win.document.body.scrollTop);
        }
        var eventBtnCloseClick = function(e) {
                _self.close();
        }
        var eventDragBgMouseMove = function(e) {
                if (!isHeadDown) return;
                if (Maya.isIE()) {
                        e = win.event;
                }
                var t = e.clientY - containTop < 0 ? 0 : e.clientY - containTop;
                Maya.CssElement(contain, {
                        left: e.clientX - containLeft + "px",
                        top: t + "px"
                });
        }
        var eventDragBgMouseUp = function(e) {
                try {
                        dragBg.releaseCapture();
                } catch(err) {}
                isHeadDown = false;

                Maya.CssElement(dragBg, {
                        display: "none"
                });
                Maya.UnEvent(dragBg, "mousemove", eventDragBgMouseMove);
                Maya.UnEvent(dragBg, "mouseup", eventDragBgMouseUp);
        }
        var eventIframeLoad = function(e) {
                removeLoading();
                if (opt.iframeAuto) {
                        if (Maya.isIE()) {
                                var h = win.frames[iframeName].document.body.offsetHeight;
                        } else {
                                var h = win.frames[iframeName].document.documentElement.offsetHeight;
                        }
                        Maya.CssElement(content, {
                                height: h + "px"
                        });
                        if (Maya.isIE6()) {
                                toggleLayout();
                        }
                        saveWidth = contain.offsetWidth;
                        saveHeight = content.offsetHeight;
                        if (opt.position == "center") {
                                var ch = h + eleParam.boxBorHeight + eleParam.headHeight + eleParam.wrapperBorHeight + eleParam.btnsHeight;
                                var t = parseInt((winScreen.clientHeight - ch) / 2);
                                if (t < 0) t = 0;
                                if (Maya.isIE6() || !opt.effect) {
                                        Maya.CssElement(contain, {
                                                top: t + "px"
                                        })
                                } else {
                                        $(contain).animate({
                                                top: t + "px"
                                        },
                                        500);
                                }
                        }
                }
                onShow();
                if (!opt.iframeReload) {
                        Maya.UnEvent(iframe, "load", eventIframeLoad);
                }
        }
        var inlineLoad = function() {
                if (opt.inlineAuto) {
                        Maya.CssElement(content, {
                                height: "auto"
                        });
                }
                var h = tmp.scrollHeight;
                content.innerHTML = tmp.innerHTML;
                win.document.body.removeChild(tmp);
                if (opt.inlineAuto) {
                        if (opt.position == "center") {
                                var t = parseInt((winScreen.clientHeight - h - eleParam.boxBorHeight - eleParam.headHeight - eleParam.wrapperBorHeight - eleParam.btnsHeight) / 2);
                                t = t < 0 ? 0 : t;
                                if (opt.effect) {
                                        $(contain).animate({
                                                top: t + "px"
                                        },
                                        200);
                                } else {
                                        Maya.CssElement(contain, {
                                                top: t + "px"
                                        });
                                }
                        }
                }
                onShow();
        }
        var checkBoxContain = function() {
                if (typeof(win.__boxContain) == "undefined") {
                        win.__boxContain = Maya.CreateElement(win, "div", {
                                className: "box_contain"
                        });
                        win.document.body.appendChild(__boxContain);
                }
                getScreenInfo();
                Maya.CssElement(__boxContain, {
                        top: winScreen.clientHeight - __boxContain.offsetHeight + "px"
                })
        }
        var toggleLayout = function() {
                Maya.CssElement(contain, {
                        width: opt.width + 1 + "px"
                });
                win.setTimeout(function() {
                        Maya.CssElement(contain, {
                                width: opt.width + "px"
                        });
                },
                1);
        }
        var createDrop = function() {
                dragBg = win.document.getElementById("dragBg");
                if (dragBg) {
                        Maya.CssElement(dragBg, {
                                display: "block"
                        });
                } else {
                        dragBg = Maya.CreateElement(win, "div", {
                                id: "dragBg",
                                className: "dragBg"
                        });
                        dragBg.onselectstart=function(){return false;}
                        Maya.CssElement(dragBg, {
                                //width : winScreen.width+"px",
                                width: "100%",
                                height: winScreen.height + "px"
                        });
                        win.document.body.appendChild(dragBg);
                }
        }
        var onShow = function() {
                if (typeof(opt.onShow) == "function") {
                        opt.onShow();
                }
        }
        var createElement = function() {
                //创建背景
                if (opt.overlayShow) {
                        overlay = Maya.CreateElement(win, "div", {
                                className: opt.skin + "_overlay"
                        });
                        Maya.CssElement(overlay, {
                                opacity: opt.overlayAlpha,
                                filter: "alpha(opacity=" + opt.overlayAlpha * 100 + ")",
                                width: winScreen.width + "px",
                                height: winScreen.height + "px"
                        });
                        win.document.body.appendChild(overlay);
                }
                //创建最外部容器
                contain = Maya.CreateElement(win, "div", {
                        type: "__maya_box",
                        className: opt.skin + "_contain"
                });
                contain.onselectstart=function(){return false;}
                Maya.CssElement(contain, {
                        width: opt.width + "px",
                        left: -9000 + "px",
                        top: -9000 + "px"
                });
                Maya.BindEvent(contain, "mousedown",
                function() {
                        _self.setIndex(this);
                });
                box = Maya.CreateElement(win, "div", {
                        type: "__maya_box",
                        className: opt.skin + "_box"
                });
                contain.appendChild(box);
                //创建按妞容器
                bpanel = Maya.CreateElement(win, "div", {
                        className: opt.skin + "_bpanel"
                });
                box.appendChild(bpanel);

                //创建头部
                head = Maya.CreateElement(win, "div", {
                        className: opt.skin + "_head"
                });
                box.appendChild(head);
                head.innerHTML = '<div class="' + opt.skin + '_head_contain"><div class="' + opt.skin + '_head_text">' + opt.text + '</div></div>';


                //创建内容容器
                wrapper = Maya.CreateElement(win, "div", {
                        className: opt.skin + "_wrapper"
                });
                box.appendChild(wrapper);
                //如果允许拖动
                if (opt.allowDrag) {
                        //head单击事件
                        Maya.BindEvent(head, "mousedown",
                        function(e) {
                                getScreenInfo();
                                isHeadDown = true;
                                if (Maya.isIE()) {
                                        e = win.event;
                                }
                                containLeft = e.clientX - parseInt(Maya.CssGet(contain).left);
                                containTop = e.clientY - parseInt(Maya.CssGet(contain).top);
                                //创建拖动层
                                createDrop();
                                //拖动层 移动事件
                                try {
                                        dragBg.setCapture();
                                } catch(e) {}
                                Maya.BindEvent(dragBg, "mousemove", eventDragBgMouseMove);

                                //拖动层 鼠标弹起事件
                                Maya.BindEvent(dragBg, "mouseup", eventDragBgMouseUp);
                        })
                }

                
                //如果允许关闭按钮 
                if (opt.btnClose) {
					//创建关闭按钮
					btnClose = Maya.CreateElement(win, "div", {
							className: opt.skin + "_btn_bg ",
							title: "关闭"
					});
					bpanel.appendChild(btnClose);
                        Maya.BindEvent(btnClose, "click", eventBtnCloseClick);
                        Maya.attrElement(btnClose, {
                                className: btnClose.className + opt.skin + "_btn_close"
                        });
                }
                //如果允许最大化按钮
                if (opt.btnMaximize) {
					//创建最大化按钮
					btnMaximize = Maya.CreateElement(win, "div", {
							className: opt.skin + "_btn_bg ",
							title: "最大化"
					});
					bpanel.appendChild(btnMaximize);
                        Maya.BindEvent(btnMaximize, "click",
                        function() {
                                _self.maximize();
                        });
                        Maya.attrElement(btnMaximize, {
                                className: btnMaximize.className + opt.skin + "_btn_maximize"
                        });
                }
                //如果允许折叠按钮
                if (opt.btnFold) {
					//创建折叠按钮
					btnFold = Maya.CreateElement(win, "div", {
							className: opt.skin + "_btn_bg ",
							title: "折叠"
					});
					bpanel.appendChild(btnFold);
                        Maya.BindEvent(btnFold, "click",
                        function() {
                                _self.fold();
                        });
                        Maya.attrElement(btnFold, {
                                className: btnFold.className + opt.skin + "_btn_fold"
                        });
                }
                //如果允许最小化按钮
                if (opt.btnMinimize) {
					//创建最小化
					btnMinimize = Maya.CreateElement(win, "div", {
							className: opt.skin + "_btn_bg ",
							title: "最小化"
					});
					bpanel.appendChild(btnMinimize);
                        Maya.BindEvent(btnMinimize, "click",
                        function() {
                                _self.minimize();
                        });
                        Maya.attrElement(btnMinimize, {
                                className: btnMinimize.className + opt.skin + "_btn_minimize"
                        });
                }
                //创建内容区域
                content = Maya.CreateElement(win, "div", {
                        className: opt.skin + "_content"
                });
                wrapper.appendChild(content);
                //创建Loading
                loading = Maya.CreateElement(win, "div", {
                        className: opt.skin + "_loading"
                });
                content.appendChild(loading);
                //如果提供了按钮
                if ((opt.btns) != null) {
                        btns = Maya.CreateElement(win, "div", {
                                className: opt.skin + "_btns"
                        });
                        wrapper.appendChild(btns);
                        for (var i = 0; i < opt.btns.length; i++) { (function(s) {
                                        var btn = Maya.CreateElement(win, "input", {
                                                className: opt.skin + "_btns_btn",
                                                type: "button",
                                                hidefocus : "true",
                                                value: opt.btns[s].text
                                        });
                                        btns.appendChild(btn);
                                        //如果是取消按钮
                                        if (opt.btns[s].isCancel) {
                                                Maya.BindEvent(btn, "click",
                                                function() {
                                                        _self.close();
                                                });
                                        } else if (typeof(opt.btns[s].onClick) == "function") {
                                                var fun = opt.btns[s].onClick;
                                                var callback = function(e) {
                                                        if (Maya.isIE()) {
                                                                var e = win.event;
                                                        }
                                                        //回调参数：box对象，事件目标对象，事件对象
                                                        fun(_self, this, e);
                                                }
                                                Maya.BindEvent(btn, "click", callback);
                                        } else {}
                                })(i);
                        }
                }

                //渲染box布局
                renderLayout();
        }
        var setContentHeight = function() {
                eleParam.containHeight = contain.offsetHeight;
                eleParam.headHeight = head.offsetHeight;
                eleParam.wrapperHeight = wrapper.offsetHeight;
                eleParam.btnsHeight = 0;
                if ((opt.btns) != null) {
                        btnsHeight = btns.offsetHeight;
                }
                eleParam.boxBorHeight = eleParam.containHeight - eleParam.headHeight - eleParam.wrapperHeight;
                eleParam.wrapperBorHeight = eleParam.wrapperHeight - eleParam.btnsHeight;
                eleParam.contentHeight = opt.height - eleParam.boxBorHeight - eleParam.headHeight - eleParam.btnsHeight - eleParam.wrapperBorHeight;

                Maya.CssElement(content, {
                        height: eleParam.contentHeight + "px"
                });
        }
        var renderLayout = function() {
                win.document.body.appendChild(contain);
                _self.setIndex(contain);
                setContentHeight();

                if (typeof(opt.position) == "string") {
                        switch (opt.position) {
                        default:
                        case "center":
                                var pos = {
                                        left: parseInt((winScreen.clientWidth - opt.width) / 2) + winScreen.scrollLeft + "px",
                                        top: parseInt((winScreen.clientHeight - contain.offsetHeight) / 2) + winScreen.scrollTop + "px"
                                }
                                break;
                        case "centerTop":
                                var pos = {
                                        left: parseInt((winScreen.clientWidth - opt.width) / 2) + winScreen.scrollLeft + "px",
                                        top: 0 + "px"
                                }
                                break;
                        case "centerBottom":
                                var pos = {
                                        left: parseInt((winScreen.clientWidth - opt.width) / 2) + winScreen.scrollLeft + "px",
                                        top: "",
                                        bottom: 0 + "px"
                                }
                                break;
                        case "leftTop":
                                var pos = {
                                        left: 0 + "px",
                                        top: 0 + "px"
                                }
                                break;
                        case "leftBottom":
                                var pos = {
                                        left: 0 + "px",
                                        top: "",
                                        bottom: 0 + "px"
                                }
                                break;
                        case "rightBottom":
                                var pos = {
                                        left: "",
                                        top: "",
                                        right: 0 + "px",
                                        bottom: 0 + "px"
                                }
                                break;
                        case "rightTop":
                                var pos = {
                                        left: "",
                                        right: 0 + "px",
                                        top: 0 + "px"
                                }
                                break;
                        }
                } else {
                        var pos = opt.position;
                }

                Maya.CssElement(contain, pos);
                if (opt.effect && !Maya.isIE()) {
                        Maya.CssElement(contain, {
                                display: "none"
                        });
                        $(contain).slideDown(100,
                        function() {
                                setContent();
                        });
                } else {
                        setContent();
                }

        }
        var setContent = function() {
                Maya.CssElement(content, {
                        height: opt.height + "px"
                });
                if (opt.position == "center") {
                        var t = parseInt((winScreen.clientHeight - opt.height - eleParam.boxBorHeight - eleParam.headHeight - eleParam.wrapperBorHeight - eleParam.btnsHeight) / 2);
                        t = t < 0 ? 0 : t;
                        Maya.CssElement(contain, {
                                top: t + "px"
                        });
                }
                switch (opt.type) {
                default:
                case "iframe":
                        iframe = Maya.CreateElement(win, "iframe", {
                                id: iframeName,
                                name: iframeName,
                                src: opt.url,
                                scrolling: opt.iframeScroll,
                                frameBorder: 0,
                                className: opt.skin + "_iframe"
                        });
                        content.appendChild(iframe);
                        Maya.BindEvent(iframe, "load", eventIframeLoad);
                        break;
                case "inline":
                        tmp = Maya.CreateElement(win, "div");
                        Maya.CssElement(tmp, {
                                position: "absolute",
                                width: opt.width + "px",
                                left: "-9999px",
                                top: "-9999px"
                        });
                        var tc = Maya.CreateElement(win, "div");
                        if (opt.isConfirm) {
                                opt.inlineAuto = true;
                                Maya.attrElement(tc, {
                                        className: opt.skin + "_confirm"
                                })
                        } else if (opt.isAlert) {
                                opt.inlineAuto = true;
                                Maya.attrElement(tc, {
                                        className: opt.skin + "_alert"
                                })
                        }
                        tmp.appendChild(tc);
                        win.document.body.appendChild(tmp);
                        if (opt.url != "") {
                                $(tc).load(opt.url,
                                function() {
                                        inlineLoad();
                                });
                        } else {
                                tc.innerHTML = opt.chtml;
                                inlineLoad();
                        }
                        break;
                }
        }
        var removeLoading = function() {
                try {
                        if (Maya.isIE() || !opt.effect) {
                                content.removeChild(loading);
                        } else {
                                $(loading).fadeOut(1000,
                                function() {
                                        $(loading).remove();
                                })
                        }
                } catch(err) {}
        }
        var resetOpt = function() {
                if (new String(opt.width).indexOf("%") > 0) {
                        opt.width = parseInt(winScreen.clientWidth * parseInt(opt.width) / 100);
                }
                if (new String(opt.height).indexOf("%") > 0) {
                        opt.height = parseInt(winScreen.clientHeight * parseInt(opt.height) / 100);
                }
        }
        var __construct = function() {
                //保存创建的窗口
                win = cfg.win == undefined ? window: cfg.win;
                if (typeof(win.__MayaBox) == "undefined") {
                        win.__MayaBox = new Array();
                }
                //win.__MayaBox = new Array();
                win.__MayaBox.push(_self);
                //获取屏幕相关参数
                getScreenInfo();
                //配置
                for (c in cfg) {
                        opt[c] = cfg[c];
                }
                //重新设置参数
                resetOpt();
                if (typeof(opt.onShowBefore) == "function") {
                        opt.onShowBefore();
                }

                //创建元素
                createElement();
        }
        __construct();
}