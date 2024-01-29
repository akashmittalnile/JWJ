

 $(function() {
   var options = {
      series: [{
            name: "Plan A",
            data: [45, 52, 38, 24, 33, 26, 21, 20, 6, 8, 15, 10]
          },
          {
            name: "Plan B",
            data: [35, 41, 62, 42, 13, 18, 29, 37, 36, 51, 32, 35]
          },
          {
            name: 'Plan C',
            data: [87, 57, 74, 99, 75, 38, 62, 47, 82, 56, 45, 47]
          }

        ],
          chart: {
          height: 350,
          type: 'line',
          foreColor: '#000',
          
          toolbar: {
            show: false
          },
          zoom: {
            enabled: false
          }
        },

       
        stroke: {
          curve: 'smooth',
          width: [2, 2, 2],
          colors:['#1079c0', '#EE9E00', '#505a61'],
          lineCap: 'round',
        },
        grid: {
          borderColor: "#edeef1",
          strokeDashArray: 2,
        },

        colors: ['#1079c0', '#EE9E00', '#505a61'],

        dataLabels: {
          enabled: false,

        },
        legend: {
          markers: {
            fillColors: ['#1079c0', '#EE9E00', '#505a61'],
          }
        },
        tooltip: {
          marker: {
            fillColors: ['#1079c0', '#EE9E00', '#505a61'],
          },

        },

       
        title: {
          text: 'Total Member',
          align: 'left'
        },

       

        fill: {
          colors: ['#1079c0', '#EE9E00', '#505a61'],
        },

        markers: {
          colors:  ['#1079c0', '#EE9E00', '#505a61'],
        },
        
        xaxis: {
          categories: ['Jan','Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false
          }
        },

        
        yaxis: {
          tickAmount: 4,
          floating: false,
          labels: {
            style: {
              colors: '#000',
            },
            offsetY: -7,
            offsetX: 0,
          },
          axisBorder: {
            show: false,
          },
          axisTicks: {
            show: false
          }
        },
        
        };

        var chart = new ApexCharts(document.querySelector("#chartBar"), options);
        chart.render();
      
  });





 $(function() {
   var options = {
      chart: {
        type: 'line',
        height: 240,
        width:250,
        sparkline: {
            enabled: true
        }
    },
    stroke: {
        show: true,
        curve: 'smooth',
        lineCap: 'butt',
        colors:['#1079c0'],
        width:2,
        dashArray: 0,
    },
   
    series: [{
        name: 'Payment received',
        data: [20, 14, 19, 10, 23, 20, 22, 9, 12]
    }],
    yaxis: {
        min: 0,
        show: false,
        axisBorder: {
            show: false
        },
    },
    xaxis: {
        show: false,
        axisBorder: {
            show: false
        },
    },
    tooltip: {
        enabled: true,
    },
    colors: ['#1079c0'],
        
    };

    var chart = new ApexCharts(document.querySelector("#chartBar1"), options);
    chart.render();
      
  });










