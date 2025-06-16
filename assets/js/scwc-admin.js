jQuery(function ($) {
    class SCWC_Admin {
        constructor() {
            this.index = $(".scwc-input-group").length; // Get initial count
            this.conditionIndex = $(".scwc_combined").length;
            this.init();
        }

        init() {
            this.eventHandlers();
            this.initColorPicker();
            this.initDynamicConditions();
        }

        eventHandlers() {
            $(document.body).on('change', '.scwc-switch-field input[type="checkbox"], .scwc-select-field select, .scwc_assign', this.toggleVisibility.bind(this));
            $(document.body).on('click', '.scwc_new_combined', this.addNewCondition.bind(this));
            $(document.body).on('click', '.scwc_combined_remove', this.removeCondition.bind(this));
            $(document.body).on('change', '.scwc_combined_selector', this.loadTermsForCondition.bind(this));
            // $(document.body).on('change', 'select.scwc_combined_selector', this.toggleCombinedOption.bind(this));
        }

        initColorPicker() {
            $('.scwc-colorpicker').wpColorPicker({
                change: function (event, ui) {
                    $(this).siblings('.colorpickpreview').css('background-color', ui.color.toString());
                },
            });
        }

        toggleVisibility(e) {
            var __this = $(e.currentTarget);
            if (__this.is('select')) {
                var target = __this.find(':selected').data('show'),
                    hideElemnt = __this.data('hide');
                $(document.body).find(hideElemnt).hide();
                $(document.body).find(target).show();
            } else {
                var target = __this.data('show');
                $(document.body).find(target).toggle();
            }
        }

        // Initialize Dynamic Conditions (Add and Remove Conditions)
        initDynamicConditions() {
            // Initialize Select2 for all the existing condition select boxes
            $('.scwc_combined_val select').select2({
                placeholder: 'Select terms...',
                width: '100%',
            });

            // Pre-load the terms for each condition if any are already set
            $('.scwc_combined_selector').each((index, element) => {
                const conditionType = $(element).val();
                if (conditionType) {
                    this.loadTermsForCondition({ currentTarget: element });
                }
            });
        }

        loadTermsForCondition(e) {
            const conditionSelect = $(e.currentTarget);
            const selectedCondition = conditionSelect.val();
            const wrapper = conditionSelect.closest('.scwc_combined');
        
            // Hide all value selectors first
            wrapper.find('.scwc_combined_val select').hide();
        
            // Show only the relevant one
            wrapper.find(`.scwc_val_${selectedCondition}`).show().trigger('change');
        }

        addNewCondition(e) {
            e.preventDefault();
        
            this.conditionIndex++; // increment for uniqueness
            const index = this.conditionIndex;
        
            var newCondition = $('.scwc_combined:first').clone();  // Clone the first condition
        
            // Update the name attributes with a dynamic index
            newCondition.find('.scwc_combined_selector').attr('name', `scwc_combined[${index}][apply]`);
            newCondition.find('.scwc_combined_compare').attr('name', `scwc_combined[${index}][compare]`);
            newCondition.find('.scwc_combined_val').html(`
                <select class="scwc_combined_val scwc_apply_terms" multiple name="scwc_combined[${index}][terms][]"></select>
            `);
        
            // Re-initialize Select2 after cloning
            newCondition.find('.scwc_combined_val select').select2({
                placeholder: 'Select terms...',
                width: '100%',
            });
        
            // Insert the new condition before the "Add condition" button
            $('.scwc_add_combined').before(newCondition);
        }
        

        // Remove a condition row
        removeCondition(e) {
            e.preventDefault();
            $(e.currentTarget).closest('.scwc_combined').remove();
        }

        toggleCombinedOption(e) {
            var __this = $(e.currentTarget);

            if (__this.is('select')) {
                var wrapper     = __this.closest('.scwc_combined').find('.scwc_combined_val_wrap'),
                    target      = __this.find(':selected').data('show'),
                    hideElemnt  = __this.data('hide');

                wrapper.find(hideElemnt).hide();
                wrapper.find(target).show();
            }
        }


    }

    new SCWC_Admin();
});