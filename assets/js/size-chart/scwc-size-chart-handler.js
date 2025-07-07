'use strict';

jQuery(function ($) {
    class SCWC_Chart_Size_Handler {
        constructor() {
            this.tableSelector = '.scwc-table-form';
            this.init();
        }

        init() {
            this.bindEvents();
            this.initColorPickers(document);
            // Sync all tables on load
            $(this.tableSelector).each((i, el) => {
                this.updateTableData($(el));
            });
        }

        bindEvents() {
            const tableSelector = this.tableSelector;

            $( document.body ).on( 'click', tableSelector + ' .scwc-add-row', this.addRow.bind( this ) );
            $( document.body ).on( 'click', tableSelector + ' .scwc-remove-row', this.removeRow.bind( this ) );
            $( document.body ).on( 'click', tableSelector + ' .scwc-add-col', this.addColumn.bind( this ) );
            $( document.body ).on( 'click', tableSelector + ' .scwc-remove-col', this.removeColumn.bind( this ) );
            $( document.body ).on( 'keyup', tableSelector + ' .scwc-cell-input', (e) => {
                    const table = $(e.currentTarget).closest(this.tableSelector);
                    this.updateTableData(table);
                } );
        }

        addRow(e) {
            e.preventDefault();
            const table = $(e.currentTarget).closest(this.tableSelector),
                row     = $(e.currentTarget).closest('tr'),
                newRow  = row.clone(true);

            newRow.find('input').val('');
            row.after(newRow);
            this.updateTableData(table);
        }

        removeRow(e) {
            e.preventDefault();
            const table = $(e.currentTarget).closest(this.tableSelector),
                row     = $(e.currentTarget).closest('tr');

            if (table.find('tbody tr').length > 1) {
                row.remove();
                this.updateTableData(table);
            }
        }

        addColumn(e) {
            e.preventDefault();
            const table  = $(e.currentTarget).closest(this.tableSelector),
                th       = $(e.currentTarget).closest('th'),
                colIndex = th.index();

            const newHeader = $(`
                <th>
                    <div class="scwc-col-actions">
                        <button type="button" class="button scwc-add-col">Add</button>
                        <button type="button" class="button scwc-remove-col">Remove</button>
                    </div>
                </th>
            `);
            th.after(newHeader);

            table.find('tbody tr').each(function () {
                const cells = $(this).find('td');
                const newCell = $('<td><input type="text" name="scwc_table_cell[]" class="scwc-cell-input" value="" /></td>');
                cells.eq(colIndex).after(newCell);
            });

            this.updateTableData(table);
        }

        removeColumn(e) {
            e.preventDefault();
            const table = $(e.currentTarget).closest(this.tableSelector);
            const th = $(e.currentTarget).closest('th');
            const colIndex = th.index();

            if (table.find('thead th').length > 2) {
                th.remove();
                table.find('tbody tr').each(function () {
                    $(this).find('td').eq(colIndex).remove();
                });
                this.updateTableData(table);
            }
        }

        updateTableData(table) {
            const tableData = [];

            table.find('tbody tr').each(function () {
                const row = [];
                $(this).find('td:not(:first-child)').each(function () {
                    const inputVal = $(this).find('input').val().trim();
                    row.push(inputVal || '&nbsp;');
                });
                tableData.push(row);
            });
            
            const hiddenInput = table.closest('.scwc-table-container').find('.scwc-table-data');
            if (hiddenInput.length) {
                hiddenInput.val(JSON.stringify(tableData));
            }
        }

        initColorPickers(context) {
            $(context).find('.scwc-color-picker').each(function () {
                $(this).wpColorPicker();
            });
        }
    }

    new SCWC_Chart_Size_Handler();
});