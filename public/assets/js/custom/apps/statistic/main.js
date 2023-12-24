async function getStatisticData() {
    try {
        const response = await axios.get('/admin/get-statistic');
        console.log(response.data);
        renderChartWeek(response.data.ordersByWeek);
        renderChartMonth(response.data.ordersByMonth);
        renderChartYear(response.data.ordersByYear);
        renderPlan(response.data.plan);
        renderCategories(response.data.categories);
    } catch (error) {
        console.error('Đã xảy ra lỗi', error);
    }
}
getStatisticData();
function renderChartWeek(data){
    const optionsWeek = {
        series: [{
            name: "VNĐ",
            data: data.total
        }],
        chart: {
            type: 'area',
            height: 500,
            zoom: {
                enabled: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'straight'
        },

        title: {
            text: 'Doanh thu theo tuần',
            align: 'left'
        },
        labels: data.day,
        xaxis: {
            type: 'date',
        },
        yaxis: {
            opposite: true
        },
        legend: {
            horizontalAlign: 'left'
        }
    };
    const chartWeek = new ApexCharts(document.querySelector("#kt_charts_widget_35_chart_1"), optionsWeek);
    chartWeek.render();
}
function renderChartMonth(data){
    const optionsMonth = {
        series: [{
            name: "VNĐ",
            data: data.total
        }],
        chart: {
            type: 'area',
            height: 500,
            zoom: {
                enabled: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'straight'
        },

        title: {
            text: 'Doanh thu theo tháng',
            align: 'left'
        },
        labels: data.day,
        xaxis: {
            type: 'date',
        },
        yaxis: {
            opposite: true
        },
        legend: {
            horizontalAlign: 'left'
        }
    };
    const chartMonth = new ApexCharts(document.querySelector("#kt_charts_widget_35_chart_2"), optionsMonth);
    chartMonth.render();
}
function renderChartYear(data){
    const optionsYear = {
        series: [{
            name: "VND",
            data: data.total
        }],
        chart: {
            type: 'area',
            height: 500,
            zoom: {
                enabled: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'straight'
        },

        title: {
            text: 'Doanh thu theo năm',
            align: 'left'
        },
        labels: (data.month).map(month=>`Tháng ${month}`),
        xaxis: {
            type: 'date',
        },
        yaxis: {
            opposite: true
        },
        legend: {
            show: false
        }
    };
    const chartYear = new ApexCharts(document.querySelector("#kt_charts_widget_35_chart_3"), optionsYear);
    chartYear.render();
}
function renderPlan(data){
    const optionsPlan = {
        series: [{
            name: "Người dùng",
            data: data.total
        }],
        chart: {
            type: 'bar',
            height: 350
        },
        plotOptions: {
            bar: {
                borderRadius: 4,
                horizontal: true,
            }
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            categories: data.name,
        }
    };
    const chartPlan = new ApexCharts(document.querySelector("#kt_apexcharts_2"), optionsPlan);
    chartPlan.render();
}
function renderCategories(data){
    const optionsPlan = {
        series: [{
            name: "Cửa hàng",
            data: data.total
        }],
        chart: {
            type: 'bar',
            height: 350
        },
        plotOptions: {
            bar: {
                borderRadius: 4,
                horizontal: true,
            }
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            categories: data.name,
        }
    };
    const chartPlan = new ApexCharts(document.querySelector("#kt_card_widget_6_chart"), optionsPlan);
    chartPlan.render();
}

