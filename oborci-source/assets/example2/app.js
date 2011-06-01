Ext.onReady(function(){
        var MyWindowUi = Ext.extend(Ext.Window, {
                title: 'Oborci Example2',
                width: '100%',
                height: '100%',
                closable: false,
                draggable: false,
                resizable: false,
                layout: 'fit',
                initComponent: function() {
                        this.tbar = {
                                xtype: 'toolbar',
                                items: [
                                {
                                        xtype: 'button',
                                        text: 'Home'
                                },
                                {
                                        xtype: 'button',
                                        text: 'Master',
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
                                }
                                ]
                        };
                        this.items = [
                        {
                                xtype: 'treepanel',
                                title: 'My Tree',
                                width: 260,
                                height: 511,
                                root: {
                                        text: 'Tree Node'
                                },
                                loader: {

                        }
                        }
                        ];
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

