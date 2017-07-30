$(document).ready(function(){
  var data = {
    labels: ['A', 'B', 'C', 'D', 'E', 'F', 'G'],
    series: [
      [5, 4, 3, 7, 5, 10, 2],
      [4, 7, 6, 7, 3, 2, 5],
      [2, 5, 4, 3, 7, 1, 2]
    ]
  };
  
  var options = {
    showPoint: true,
    lineSmooth: true,
    showArea: false,
    fullWidth: true,
    chartPadding: {
      top: 20,
      right: 20,
      bottom: 0,
      left: 20
    },
    axisX: {
      scaleMinSpace: 20,
      showGrid: false,
      showLabel: true
    },
    axisY: {
      scaleMinSpace: 20,
      labelInterpolationFnc: function(v){
        return v + ' l';
      }
    },
    plugins: [
      Chartist.plugins.tooltip({
        pointClass: 'ct-circle',
        tooltipOffset: {
          x: 0,
          y: -15
        }
      })
    ]
  };

  $.getJSON("../analyzed/fuel_consumption.json", function(obj){
    console.log('ok');
    var chart2 = new Chartist.Line('.chart2', obj,  {
      showPoint: false,
      lineSmooth: false,
      showArea: false,
      fullWidth: true,
      chartPadding: {
        top: 20,
        right: 20,
        bottom: 0,
        left: 20
      },
      axisX: {
        showGrid: false,
        showLabel: true,
        labelInterpolationFnc: function(v, i) {
          return i % 50 === 0 ? v : null;
        }
      },
      axisY: {
        scaleMinSpace: 20,
        labelInterpolationFnc: function(v){
          return v;
        }
      }
    });
  })
  .done(function(){
    console.log('done');
  })
  .fail(function(){
    console.log('failed');
  })

  $.getJSON("../analyzed/fuel_consumption_10.json", function(obj){
    console.log('ok');
    var chart3 = new Chartist.Line('.chart3', obj,  {
      showPoint: true,
      lineSmooth: true,
      showArea: false,
      fullWidth: true,
      chartPadding: {
        top: 20,
        right: 20,
        bottom: 0,
        left: 20
      },
      axisX: {
        showGrid: false,
        showLabel: true
      },
      axisY: {
        scaleMinSpace: 20,
        labelInterpolationFnc: function(v){
          return v;
        }
      },
      plugins: [
        Chartist.plugins.tooltip({
          pointClass: 'ct-circle',
          tooltipOffset: {
            x: 0,
            y: -15
          }
        })
      ]
    });

    chart3.on('draw', function(data){
      if (data.type === 'point'){
        var circle = new Chartist.Svg('circle', {
          cx: [data.x],
          cy: [data.y],
          r: [5],
          'ct:value': data.value.y,
          'ct:subClass': 'serie-'+data.seriesIndex
        }, 'ct-circle');
        data.element.replace(circle);
      }
    });
  })
  .done(function(){
    console.log('done');
  })
  .fail(function(){
    console.log('failed');
  })
  
  var chart = new Chartist.Line('.chart1', data, options);

  chart.on('draw', function(data){
    if (data.type === 'point'){
      var circle = new Chartist.Svg('circle', {
        cx: [data.x],
        cy: [data.y],
        r: [5],
        'ct:value': data.value.y,
        'ct:subClass': 'serie-'+data.seriesIndex
      }, 'ct-circle');
      data.element.replace(circle);
    }
  });
});


