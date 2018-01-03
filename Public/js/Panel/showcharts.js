(function(){
	showGraph = {
			line : function(divid, data){			
				var myChart = echarts.init(divid, 'macarons');
				option = {
						title : {
					        text: data.title,
					        subtext: data.subtitle,
					        left: 'left'
					    },
					    tooltip : {
					        trigger: 'axis'
					    },
					    legend: {
					        data: data.legend
					    },
					    grid:{
					    	left:'5%',
					    	right:'5%'
					    },
					    xAxis : [
					        {
					            type : 'category',
					            boundaryGap : false,
					            data : data.date
					        }
					    ],
					    yAxis : [
					        {
					            type : 'value',
					            axisLabel : {
					                formatter: '{value}'
					            }
					        }
					    ],
					    series : [
					        {
					            name:data.legend[0],
					            type:'line',
					            data: data.report[0],
					            markPoint : {
					                data : [
					                    {type : 'max', name: '最大值'},
					                    {type : 'min', name: '最小值'}
					                ]
					            },
					            markLine : {
					                data : [
					                    {type : 'average', name: '平均值'}
					                ]
					            }
					        },
					        {
					            name:data.legend[1],
					            type:'line',
					            data:data.report[1],
					            markPoint : {
					                data : [
					                    {type : 'max', name: '最大值', valueIndex: 1},
					                    {type : 'min', name: '最小值', valueIndex: 1}
					                ]
					            },
					            markLine : {
					                data : [
					                    {type : 'average', name : '平均值'}
					                ]
					            }
					        },
					        {
					            name:data.legend[2],
					            type:'line',
					            data:data.report[2],
					            markPoint : {
					                data : [
					                    {type : 'max', name: '最大值'},
					                    {type : 'min', name: '最小值'}
					                ]
					            },
					            markLine : {
					                data : [
					                    {type : 'average', name : '平均值'}
					                ]
					            }
					        }
					    ]
					};
				
				myChart.setOption(option);
				window.onresize = myChart.resize;
			}
	}
	
})();