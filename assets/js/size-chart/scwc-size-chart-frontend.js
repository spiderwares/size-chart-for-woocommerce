jQuery(function ($) {

    class SCWCSizeChartFrontend {

        constructor() {
            this.eventHandlers();
        }

        eventHandlers() {
            $(document.body).on('click', '.scwc-size-charts-list-item', this.loadSizeChartContentHandler.bind(this)); 
            $(document.body).on('click', '.scwc-table-options span', this.toggalTable.bind(this)); 
        }

        loadSizeChartContentHandler(e) {
            e.preventDefault();
            var __this    = $(e.currentTarget),
                chartId   = __this.data('id');

            $.ajax({
                url: scwc_size_chart_vars.ajax_url,
                type: 'POST',
                data: {
                    action: 'scwc_get_size_chart_content',
                    chart_id: chartId,
                    nonce: scwc_size_chart_vars.nonce,
                },
                beforeSend: () => {
                    __this.addClass('scwc-loading');
                },
                success: function(response) {
                    if (response.success && response.data.html) {

                        if(scwc_size_chart_vars.setting.popup_library == 'featherlight'){
                            $.featherlight(response.data.html, {
                                persist: true,
                                closeOnClick: 'background',
                                closeIcon: '&#x2715;', // Optional
                                variant: 'scwc-size-chart-popup'
                            });
                        }

                        if(scwc_size_chart_vars.setting.popup_library == 'magnific'){
                            const effectClass = scwc_size_chart_vars.setting.effect || 'mfp-fade';
                            console.log(effectClass);
                            $.magnificPopup.open({
                                items: {
                                    src: '<div class="scwc-size-chart-popup ' + effectClass + '">' + response.data.html + '</div>',
                                    type: 'inline'
                                },
                                closeBtnInside: true,
                                removalDelay: 300,
                                mainClass: effectClass 
                            });
                        }

                    } else {
                        console.log('Error loading size chart:', response.data?.message || 'Unknown error');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('AJAX Error:', error);
                },
                complete: () => {
                    __this.removeClass('scwc-loading');
                }
            });
        }

        toggalTable(e) {
            e.preventDefault();
            var __this = $(e.currentTarget),
                target =  __this.data('target');

            $('.scwc-table-options span').removeClass('active');
            __this.addClass('active');
            $('.scwc-size-chart-table').hide();    
            $('.' + target).show();
        }

    }

    new SCWCSizeChartFrontend();
});