/**
 * Created With Vim7.3
 * @fileOverview : peasDialog组件
 * @author : Chen weihan <csq-3@163.com>
 * @since : 2013年05月30日 星期四 14时01分52秒
 * @filename : peasDialog.js
 * @version : v 1.0 
 * @description : peasDIalog组件
 * 实现功能 : 相对定位  绝对定位  拖拽 多个层叠 背景遮盖层 延时关闭 resize [动画,皮肤,兼容]
 * v 1.0
 * 注意outerHeight 只是获取当前元素的 边距和高，如果嵌套div有边距，这是不会计算的.
 */

;(function($){
   var peasDialog = {
          
           /**
            * 存创建的dialog
            */
           _dialogList : {},

           /**
            * 默认设置
            */
           _option : {
              followId : '',           //如果是相对，则需要该参数
              id : 'peasDialog' ,      //标识 如果document id存在,而且不是peasDialog的DOM，则替换已经存在的id DOM
              title : '对话框',        //为空默认没有头部
              ok : '确认',             //为空默认没有尾部
              position : 'absolute',   //[absolute|relative|fixed] 
              drag : true,            //[true | false]         
              lockBG : true,           //[true | false]
              timeout : 0,             //[int(num)>0,执行延迟] ms
              width : 400,
              height : 300,
              left : 'auto',           //auto 默认水平垂直居中
              top  : 'auto',
              zindex : '2013',         //层级
              overflow : 'auto',       //[scroll | hidden | auto]   
              skin : 'default',        //皮肤
              animation : 'fade-over', //[目前只支持淡入淡出] 
              onCloseAfter : function(){},       // 关闭后回调 
              onCloseBefore : function(){return true;},      // 关闭前回调 
              onOkAfter : function(){},          // 确认后回调
              onOkBefore : function(){return true;},         // 确认前回调
              content : ''         // 显示字符串  
              //onzise maxsiez 等未实现
           },
           
           _init : function() {
              this._bindEvt();
           },
             
           _bindEvt : function() {
              var tthis = this,
                  bool = false,
                  id,
                  w_w,w_h,s_h,s_w,s_left,s_top,
                  x_down,
                  y_down;
              
              $('.peasDialogClose').live('click',function() {
                    var id = $(this).parent().parent().attr('id');
                    tthis.closeDialog(id);
              });
              
              $('.peasDialogOk').live('click',function() {
                    var id = $(this).parent().parent().attr('id');
                    tthis._okDialog(id);
              });
              
              /***鼠标拖拽***/
              $('.peasDialogHead').live('mousedown',function(e) {
                    id = $(this).parent().attr('id');
                    if (tthis._dialogList[id].drag) {
			bool = true;
			x_down = e.pageX;
			y_down = e.pageY;
			w_w = $(window).width();
			w_h = $(window).height();
			s_h =  $('#' + id).outerHeight();
			s_w =  $('#' + id).outerWidth();
			s_left = $('#' + id).offset().left;
			s_top = $('#'+id).offset().top;  
                        $(this).css('cursor','move'); 
                    }
                    //console.log($('#' + id).find('.peasDialogHead').outerHeight());
                    //console.log(w_w,s_w,w_h,s_h,s_left,s_top);  
              });              

              $(document).mousemove(function(e) {
                   if (bool) {
                      
                        var x_move = e.pageX,
                            y_move = e.pageY,
                            x_change = s_left + (x_move - x_down),
                            y_change = s_top + (y_move - y_down);
                          
                         if (x_change > w_w - s_w - 15) {
                             x_change = w_w - s_w; 
                         } else if (x_change < 15) {
                             x_change = 0
                         }


                         if (y_change > w_h - s_h - 15) {
                             y_change = w_h - s_h;
                         } else if (y_change < 15) {
                             y_change = 0
                         }
                            

                         $('#'+id).css({
                             left : x_change+'px',
                             top : y_change+'px'  
                         })

                   }
              });
               
              $(document).mouseup(function() {
                      bool = false;
              });
           },
            
           openDialog : function (config) {
                config = this._mergeConfig(config);
                if ( $('#'+config.id).length > 0 ) {
                    if ($('#'+config.id).attr('data-peas') == 'peasDialog') {
                       this._showDialog(config.id);    
                    } else {
                       this._createDialog(config,'replace');                       
                    }
                } else {
                       this._createDialog(config,'append');
                }
           },
           
           closeDialog : function(id) {
                if (this._dialogList[id].onCloseBefore()) {
                    this._hideDialog(id); 
                    this._dialogList[id].onCloseAfter();
                }
           },

           destroyDialog : function(id) {
                this.closeDialog(id);
                delete this._dialogList[id]; 
                $('#'+id).remove();                  
                $('#'+id+'_lockBG').remove();
           },           

           _okDialog : function() {
                if (this._dialogList[id].onOkBefore()) {
                    this._hideDialog(id); 
                    this._dialogList[id].onOkAfter();
                }
           },
            
           _createDialog : function(config,type) {
                //缓存dialog配置属性,第三方调用关闭(只传递了id),需要执行的回调函数
                this._dialogList[config.id] = config;
                var html = this._dialogHTML(config); 
                if (type == 'append') {
                    $('body').append(html);
                } else {
                    $('#'+config.id).replaceWith(html);
                }
                this._showDialog(config.id);
           },
           
           _dialogHTML : function(config) {
                var style = this._htmlStyle(config);
                var html = '<div id='+config.id+'  class="peasDialog" data-peas="peasDialog" style="'+style.styleId+'">'
                         +    '<div class="peasDialogHead" style="'+style.styleHead+'">'
                         +          '<div class="peasDialogTitle">' + config.title + '</div><div class="peasDialogClose"></div>'
                         +    '</div>'
                         +    '<div class="peasDialogContent" style="'+ style.styleContent +'">'
                         +       config.content
                         +    '</div>'
                         +    '<div class="peasDialogFoot" style="'+style.styleFoot+'">'
                         +          '<div class="peasDialogOk"> '+ config.ok +' </div>'
                         +    '</div>'
                         + '</div>';

                if (config.lockBG) {
                    html  = this._dialogBGHTML(config) + html;
                }

                return html;
           },

           _dialogBGHTML : function(config) {
              var html = '<div  id = "'+config.id+'_lockBG" class="peasDialogLockBG" style="z-index:'+config.zindex+';position:absolute;left:0px;top:0px;width:'+ $(window).width() +'px;height:'+ $(window).height() +'px"></div>';
                  return html;
           },

           _htmlStyle : function(config) {
                var style = {};
                this._positionHandle(config);
                    
                    if (config.followId == '') {
			  style.styleId = 'width:' +config.width+ 'px'
					+ ';height:' + config.height + 'px'
					+ ';position:' + config.position  
					+ ';left:' + config.left + 'px'
					+ ';top:' + config.top + 'px'
					+ ';display:none' 
					+ ';z-index:' + config.zindex;
                    } else  {
                          var left = $('#'+config.followId).offset().left,     
                              top = $('#'+config.followId).offset().top;     
                    
                          style.styleId = 'width:' +config.width+ 'px'
					+ ';height:' + config.height + 'px'
					+ ';position:' + config.position  
					+ ';left:' + (left+config.left) + 'px'
					+ ';top:' + (top+config.top) + 'px'
					+ ';display:none' 
					+ ';z-index:' + config.zindex;
                    }                   


                    //把这些样式写死在这里，是因为这些都于计算位置有关，不能由用户随意的修改，修改了会出现BUG，也没有意义。
                    if (config.ok == '' && config.title == '') { 
                        style.styleHead = 'display:none;margin:0px;padding:5px 0px 5px 15px;overflow:hidden;height:20px;border-bottom:1px solid #ddd;';
                        style.styleContent = 'overflow:' + config.overflow + ';height:'+ (config.height) +'px';
                        style.styleFoot = 'display:none;margin:0px;padding:5px 15px;overflow:hidden;height:20px;border-top:1px solid #ddd;';
                    } else if (config.ok == '') {
                        style.styleHead = 'margin:0px;padding:5px 0px 5px 15px;overflow:hidden;height:20px;border-bottom:1px solid #ddd;';
                        style.styleContent = 'overflow:' + config.overflow + ';height:'+ (config.height-31) +'px';
                        style.styleFoot = 'display:none;margin:0px;padding:5px 15px;overflow:hidden;height:20px;border-top:1px solid #ddd;';
                    } else if (config.title == '') {
                        style.styleHead = 'display:none;margin:0px;padding:5px 0px 5px 15px;overflow:hidden;height:20px;border-bottom:1px solid #ddd;';
                        style.styleContent = 'overflow:' + config.overflow + ';height:'+ (config.height-31) +'px';
                        style.styleFoot = 'margin:0px;padding:5px 15px;overflow:hidden;height:20px;border-top:1px solid #ddd;';
                    } else {
                        style.styleHead = 'margin:0px;padding:5px 0px 5px 15px;overflow:hidden;height:20px;border-bottom:1px solid #ddd;';
                        style.styleContent = 'overflow:' + config.overflow + ';height:'+ (config.height-31-31) +'px';
                        style.styleFoot = 'margin:0px;padding:5px 15px;overflow:hidden;height:20px;border-top:1px solid #ddd;';
                    }
                    return style;
           },

           resize : function(obj) {
                   var config = this._dialogList[obj.id];
		       $('#'+obj.id).css({width:obj.width+'px',height:obj.height+'px'});          
		       if (config.ok == '' && config.title == '') { 
			    height = obj.height;          
			} else if (config.ok == '') {
			    height = obj.height-31;          
			} else if (config.title == '') {
			    height = obj.height-31;          
			} else {
			    height = obj.height-31-31;          
			}
		        $('#'+obj.id).find('.peasDialogContent').css({height:height+'px'});  
           },
            
           _positionHandle : function(config) {
                 var w_w = $(window).width(),
                     w_h = $(window).height();
               
                 if (config.left == 'auto') {
                     if (config.width < w_w) {
                         config.left = (w_w - config.width) / 2;
                     } else {
                         config.left = 0;
                     }
                 }
                
                 if (config.top == 'auto') {
                     if (config.height < w_h) {
                         config.top = (w_h - config.height) / 2;
                     } else {
                         config.top = 0;
                     }
                 }
           },
           
           _showDialog : function(id) {
               var tthis = this;
               $('#'+id).fadeIn();
               if (this._dialogList[id].timeout > 0 ) {
                  setTimeout(function() {
                     tthis._hideDialog(id);
                  }, this._dialogList[id].timeout);
               }

               if (this._dialogList[id].lockBG) {
                  $('#'+id+'_lockBG').show();
               }
           },
           
           _hideDialog : function(id) {
               $('#'+id).fadeOut();
               if (this._dialogList[id].lockBG) {
                  $('#'+id+'_lockBG').hide();
               }
           },

           _mergeConfig : function(config) {
                config = config || {};
                //合并默认配置
	        for (var i in this._option) {
		     if (config[i] === undefined) config[i] = this._option[i];     
	        };
                return config; 
           },
       };
       $.peasDialog = peasDialog;
       $.peasDialog._init();
})($);
