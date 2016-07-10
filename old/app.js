Ext.setup({
  onReady: function(options) {
    Ext.create('Ext.form.Panel', {
      fullscreen : true,
      items: [{
          docked: 'top',
          xtype: 'titlebar',
          title: 'SliderExtended'
        },{
          xtype : 'fieldset',
          title: 'Rating of the day',
          items : [{
              xtype: 'sliderfieldextended',
              name: 'slider_integer',
              labelText: 'Integer',
              label: 'Integer',
              value: 5,
              minValue: 1,
              maxValue: 10
            }]
        }]
    });
  }
});
