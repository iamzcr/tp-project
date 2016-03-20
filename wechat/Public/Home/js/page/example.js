/**
 * Created by jfengjiang on 2015/9/11.
 */

$(function () {

    var stack = [];
    var path,age,height,weight,radio1,radio2,checkbox1,checkbox2;
    path = $('.container').attr('data-url');
    var $container = $('.js_container');
    $container.on('click', '.js_cell[data-id]', function () {
        var id = $(this).data('id');

        var $tpl = $($('#tpl_' + id).html()).addClass('slideIn').addClass(id);
        $container.append($tpl);
        stack.push($tpl);
        history.pushState({id: id},'', '?step='+ id);
        $($tpl).on('webkitAnimationEnd', function (){
            $(this).removeClass('slideIn');
        }).on('animationend', function (){
            $(this).removeClass('slideIn');
        });
        if(id === "step_1"){
            age = $('#age').attr("value");
            height = $('#height').attr("value");
            weight = $('#weight').attr("value");
        }
        if(id === "step_2"){
            radio1 = $("input[name='radio1']:checked").val()
        }
        if(id === "step_3"){
            radio2 = $("input[name='radio2']:checked").val()
        }
        if(id === "step_4"){
            var tmp = [];
            $('input[name="checkbox1"]:checked').each(function(){
                tmp.push($(this).val());
            });
            checkbox1 = tmp
        }
        // tooltips
        if (id === "step_5") {
            var temp =[];
            $('input[name="checkbox2"]:checked').each(function(){
                temp.push($(this).val());
            });
            checkbox2 = temp

            $('#loadingToast').show();
            setTimeout(function () {
                $('#loadingToast').hide();

                $.post(
                    path,
                    {   age: age,
                        heights: height,
                        weights: weight,
                        if_harm: radio1,
                        bear_age: radio2,
                        habits: checkbox1,
                        problem: checkbox2
                    },
                    function(response){
                        console.log(response)
                    })

               // 路径配置
                require.config({
                    paths: {
                        echarts: 'http://echarts.baidu.com/build/dist'
                    }
                });

                // 使用
                require(
                    [
                        'echarts',
                        'echarts/chart/line' // 使用柱状图就加载bar模块，按需加载
                    ],
                    function (ec) {
                        // 基于准备好的dom，初始化echarts图表
                        var myChart = ec.init(document.getElementById('main'));

                        var option = {
                            title: {
                                text: "测试结果",
                                x: "center"
                            },
                            tooltip: {
                                trigger: "item",
                                formatter: "{a} <br/>{b} : {c}"
                            },
                            legend: {
                                x: 'left',
                                data: ["健康值"]
                            },
                            xAxis: [
                                {
                                    type: "category",
                                    name: "x",
                                    splitLine: {show: false},
                                    data: ["25以下", "25-29", "30-34", "35-39", "40-44", "45-49", "50-54", "55-59", "60-64","64-69","69以上  "]
                                }
                            ],
                            yAxis: [
                                {
                                    type: "log",
                                    name: "y"
                                }
                            ],
                            toolbox: {
//                                show: true,
                                feature: {
                                    mark: {
                                        show: true
                                    },
                                    dataView: {
                                        show: true,
                                        readOnly: true
                                    },
                                    restore: {
                                        show: true
                                    },
                                    saveAsImage: {
                                        show: true
                                    }
                                }
                            },
                            calculable: true,
                            series: [
//                                {
//                                    name: "3的指数",
//                                    type: "line",
//                                    data: [1, 3, 9, 27, 81, 247, 741, 2223, 6669]
//
//                                },
                                {
                                    name: "健康值",
                                    type: "line",
                                    data: [1, 2, 4, 8, 16, 32, 64, 128, 256]

                                }
                            ]
                        };

                        // 为echarts对象加载数据
                        myChart.setOption(option);
                    }
                );
            }, 2000);
        }
    });

    // webkit will fired popstate on page loaded
    $(window).on('popstate', function () {
        var $top = stack.pop();

        if (!$top) {
            return;
        }
        $top.addClass('slideOut').on('animationend', function () {
            $top.remove();
        }).on('webkitAnimationEnd', function () {
            $top.remove();
        });
    });

});