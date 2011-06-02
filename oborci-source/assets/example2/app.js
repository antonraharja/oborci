Ext.onReady(function(){
        
        
        // ------------- variables -------------
        
        
        var windowTitle = 'OborCI Example2';
        var panelTitleWest = 'Options';
        var panelTitleCenter = 'Dashboard';
        var panelHtmlCenter = 'Welcome to OborCI Project Example2';
        var labelTextLogin = 'Welcome';
        var buttonTextHome = 'Home';
        var buttonTextMain = 'Main';
        var buttonTextLogout = 'Logout';
        
        var panelItemsOptions = [{
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
        }];
        // panelItemsOptions = [{"title":"Option 1","html":"This is option 1"},{"title":"Option 2","html":"This is option 2"},{"title":"Option 3","html":"This is option 3"}];

        var menuItemsMain = [{
                xtype: 'menuitem',
                text: 'Role Management',
                handler: onMenuMainClick
        },{
                xtype: 'menuitem',
                text: 'User Management',
                handler: onMenuMainClick
        },{
                xtype: 'menuitem',
                text: 'Screen Management',
                handler: onMenuMainClick
        },{
                xtype: 'menuitem',
                text: 'Menu Management',
                handler: onMenuMainClick
        }];


        // ------------- UIs -------------
        
        
        var MyWindowUi = Ext.extend(Ext.Window, {
                title: windowTitle,
                width: '100%',
                height: '100%',
                closable: false,
                draggable: false,
                resizable: false,
                layout: 'border',
                initComponent: function() {
                        this.items = [{
                                id: 'panelNorth',
                                xtype: 'panel',
                                title: '',
                                autoHeight: true,
                                layout: 'column',
                                region: 'north',
                                margins: '5 5 0 5',
                                tbar: [{
                                        id: 'labelLogin',
                                        xtype: 'label',
                                        text: labelTextLogin,
                                        padding: '0 0 0 5',
                                                        
                                },{
                                        xtype: 'tbfill' // or '->'
                                },{
                                        id: 'buttonHome',
                                        xtype: 'button',
                                        handler: onButtonHomeClick,
                                        text: buttonTextHome
                                },{
                                        id: 'buttonMain',
                                        xtype: 'button',
                                        text: buttonTextMain,
                                        menu: {
                                                id: 'menuMain',
                                                xtype: 'menu',
                                                items: menuItemsMain
                                        }
                                },
                                {
                                        id: 'buttonLogout',
                                        xtype: 'button',
                                        handler: onButtonLogoutClick,
                                        text: buttonTextLogout
                                }]
                        },{
                                id: 'panelWest',
                                xtype: 'panel',
                                title: panelTitleWest,
                                width: 200,
                                height: '100%',
                                layout: 'accordion',
                                collapsible: true,
                                region: 'west',
                                margins: '5 0 5 5',
                                split: true,
                                items: panelItemsOptions
                        },{
                                id: 'panelCenter',
                                xtype: 'panel',
                                title: panelTitleCenter,
                                width: '100%',
                                height: '100%',
                                layout: 'fit',
                                region: 'center',
                                margins: '5 5 5 0',
                                split: true,
                                html: panelHtmlCenter
                        }];
                        MyWindowUi.superclass.initComponent.call(this);
                }
        });        
        

        // ------------- functions -------------
        

        function onButtonHomeClick() {
                window.location.href = 'welcome';
        }
        
        function onButtonLogoutClick() {
                Ext.Msg.show({
                        title: 'Logout',
                        msg: 'Do you really want to logout ?',
                        buttons: Ext.Msg.YESNO,
                        fn: processLogout,
                        icon: Ext.window.MessageBox.QUESTION
                });
        }
        
        function onMenuMainClick(menu, item, e){
                Ext.Msg.alert('text:' + item.text + ' id:' + item.getId());
        }
        
        function processLogout(btn) {
                if (btn=='yes') {
                        window.location.href = 'process/logout/ajax';
                }
        }
        

        // ------------- logic -------------
        
        Ext.define("Welcome", {
                extend: 'Ext.data.Model',
                fields: ['windowTitle', 'panelTitleWest', 'panelTitleCenter', 'panelHtmlCenter', 'labelTextLogin', 'buttonTextHome', 'buttonTextMain', 'buttonTextLogout', 'panelItemsOptions', 'menuItemsMain']
        });
        
        var store = new Ext.data.Store({
                model: 'Welcome',
                proxy: {
                        type: 'ajax',
                        url : 'welcome/app/ajax',
                        reader: {
                                type: 'json',
                                root: 'returns'
                        }
                }
        });
        
        store.load({callback: execLogic});
        
        function execLogic() {
                var w = store.first();
                var x;
                var y;
                
                y = new MyWindowUi();
                y.title = w.get('windowTitle');

                x = Ext.getCmp('panelWest');
                x.title = w.get('panelTitleWest');
                x.removeAll();
                x.add(w.get('panelItemsOptions'));
                
                x = Ext.getCmp('panelCenter');
                x.title = w.get('panelTitleCenter');
                x.html = w.get('panelHtmlCenter');
                
                x = Ext.getCmp('labelLogin');
                x.text = w.get('labelTextLogin');
                        
                x = Ext.getCmp('buttonHome');
                x.text = w.get('buttonTextHome');
                
                x = Ext.getCmp('buttonMain');
                x.text = w.get('buttonTextMain');
                
                x = Ext.getCmp('buttonLogout');
                x.text = w.get('buttonTextLogout');
                
                x = Ext.getCmp('menuMain');
                x.removeAll();
                x.add(w.get('menuItemsMain'));
                x.addListener('click', onMenuMainClick);
                
                y.show();
        }

});
