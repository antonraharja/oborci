Ext.onReady(function(){
        var MyWindowUi = Ext.extend(Ext.Window, {
                title: 'Oborci Example2',
                width: '100%',
                height: '100%',
                closable: false,
                draggable: false,
                resizable: false,
                layout: 'border',
                initComponent: function() {
                        this.items = [{
                                xtype: 'panel',
                                title: '',
                                autoHeight: true,
                                layout: 'column',
                                region: 'north',
                                margins: '5 5 0 5',
                                tbar: [{
                                        xtype: 'button',
                                        text: 'Help',
                                        handler: onMenuClick
                                                        
                                },{
                                        xtype: 'tbfill' // or '->'
                                },{
                                        xtype: 'button',
                                        text: 'Home',
                                        handler: onMenuClick
                                },{
                                        xtype: 'button',
                                        text: 'Main',
                                        menu: {
                                                xtype: 'menu',
                                                items: [
                                                {
                                                        xtype: 'menuitem',
                                                        text: 'Role Management',
                                                        handler: onMenuClick
                                                },
                                                {
                                                        xtype: 'menuitem',
                                                        text: 'User Management',
                                                        handler: onMenuClick
                                                },
                                                {
                                                        xtype: 'menuitem',
                                                        text: 'Screen Management',
                                                        handler: onMenuClick
                                                },
                                                {
                                                        xtype: 'menuitem',
                                                        text: 'Menu Management',
                                                        handler: onMenuClick
                                                }
                                                ]
                                        }
                                },
                                {
                                        xtype: 'button',
                                        text: 'Logout',
                                        handler: onLogoutClick
                                }]
                        },{
                                xtype: 'panel',
                                title: 'Options',
                                width: 200,
                                height: '100%',
                                layout: 'accordion',
                                collapsible: true,
                                region: 'west',
                                margins: '5 0 5 5',
                                split: true,
                                items: [{
                                        title: 'Panel 1',
                                        html: 'Content of Panel 1'
                                },{
                                        title: 'Panel 2',
                                        html: 'Content of Panel 2'
                                },{
                                        title: 'Panel 3',
                                        html: 'Content of Panel 3'
                                },{
                                        title: 'Panel 4',
                                        html: 'Content of Panel 4'
                                },{
                                        title: 'Panel 5',
                                        html: 'Content of Panel 5'
                                }]
                        },{
                                xtype: 'panel',
                                title: 'Dashboard',
                                width: '100%',
                                height: '100%',
                                layout: 'fit',
                                region: 'center',
                                margins: '5 5 5 0',
                                split: true
                        }];
                        MyWindowUi.superclass.initComponent.call(this);
                }
        });        
        
        var cmp = new MyWindowUi();
        cmp.show();

        function onMenuClick(item){
                Ext.Msg.alert(item.text);
        }
        
        function onLogoutClick() {
                Ext.Msg.show({
                        title: 'Logout',
                        msg: 'Do you really want to logout ?',
                        buttons: Ext.Msg.YESNO,
                        fn: processLogout,
                        icon: Ext.window.MessageBox.QUESTION
                });
        }
        
        function processLogout(btn) {
                if (btn=='yes') {
                        window.location.href = 'process/logout';
                }
        }
});

