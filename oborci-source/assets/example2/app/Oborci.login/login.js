Ext.onReady(function(){
    Ext.QuickTips.init();
    var login = new Ext.FormPanel({ 
        labelWidth:80,
        url:'process/login', 
        frame:true, 
        title:'Please Login', 
        defaultType:'textfield',
	monitorValid:true,
        items:[{ 
                fieldLabel:'Username', 
                name:'username', 
                allowBlank:false 
            },{ 
                fieldLabel:'Password', 
                name:'password', 
                inputType:'password', 
                allowBlank:false 
            }],
        buttons:[{ 
                text:'Login',
                formBind: true,	 
                handler:function(){ 
                    login.getForm().submit({ 
                        method:'POST', 
                        waitTitle:'Connecting', 
                        waitMsg:'Sending data...',
                        success:function(form, action, o){ 
                        	Ext.Msg.alert('Status', action.result.message, function(btn, text){
				   if (btn == 'ok'){
		                        var redirect = 'welcome';
		                        window.location = redirect;
                                   }
			        });
                        },
                        failure:function(form, action, o){ 
                            if(action.failureType == 'server'){ 
                                Ext.Msg.alert('Status', action.result.message); 
                            }else{ 
                                Ext.Msg.alert('Warning', 'Server is unreachable: ' + action.response.responseText); 
                            } 
                            login.getForm().reset(); 
                        } 
                    }); 
                } 
            }] 
    });
    var win = new Ext.Window({
        layout:'fit',
        width:300,
        height:150,
        closable: false,
        resizable: false,
        plain: true,
        border: false,
        items: [login]
	});
	win.show();
});
