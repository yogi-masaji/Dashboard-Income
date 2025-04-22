let table, weeklyTable, monthlyTable, formatRupiah;
$(document).ready(function() {
    formatRupiah = (number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    };
    table = $('#dailyE-Payment').DataTable({
        searching: false,
        paging: false,
        autoWidth: false,
        ordering: false,
        info: false,
        data: [],
        columns: [{
                data: 'no'
            },
            {
                data: 'payment'
            },
            {
                data: 'yesterday'
            },
            {
                data: 'today'
            }
        ]
    });

    weeklyTable = $('#weeklyE-Payment').DataTable({
        searching: false,
        paging: false,
        autoWidth: false,
        ordering: false,
        info: false,
        data: [],
        columns: [{
                data: 'no'
            },
            {
                data: 'payment'
            },
            {
                data: 'last_week'
            },
            {
                data: 'this_week'
            }
        ]
    });

    monthlyTable = $('#monthlyE-Payment').DataTable({
        searching: false,
        paging: false,
        autoWidth: false,
        ordering: false,
        info: false,
        data: [],
        columns: [{
                data: 'no'
            },
            {
                data: 'payment'
            },
            {
                data: 'last_month'
            },
            {
                data: 'this_month'
            }
        ]
    });

    runAllFetch();
})

async function runAllFetch() {
    try{
        await dailyData();
        await weeklyData();
        await monthlyData();
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}

async function dailyData() {
    try {
        
    } catch (error) {
        console.error('Error fetching daily data:', error);
    }
}

async function weeklyData() {
    try {
        
    } catch (error) {
        
    }
}