define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'mage/translate',
    'jquery-ui-modules/widget'
], function ($, modal, $t) {
    'use strict';

    $.widget('bluethinkinc.popupSurveyModal', {
        options: {
            redirectUrl: ''
        },

        /** @inheritdoc */
        _init: function () {
            let _self = this;
            
            this.isSurveyModalCreated = false;
            this.target = _self.options.target;
            this.surveyUrl = _self.options.redirectUrl;
            
            _self._showModal();
            return false;
        },

        /**
         * Show modal if created, otherwise create modal
         * @private
         */
        _showModal: function () {
            if (this.isSurveyModalCreated) {
                this.$createModal.modal('openModal');
            } else {
                this._createModal();
            }
        },

        /**
         * Create modal, trigger open and set flag
         * @private
         */
        _createModal() {
            const _self = this;
            
            let modalConfig = {
                type: 'popup',
                responsive: true,
                modalClass: 'style-modal',
                buttons: [{
                    text: $t("SURE I'LL GIVE FEEDBACK"),
                    class: 'a-btn a-btn--primary',
                    click: function (data) {
                        window.open(_self.surveyUrl, '_blank');
                        this.closeModal();
                    }
                }, 
                {
                    text: $t("NO THANKS"),
                    class: 'a-btn a-btn--secondary',
                    click: function (data) {
                        this.closeModal();
                    }
                }]
            };

            let $popup = $(_self.target).modal(modalConfig);
            $popup.modal('openModal');

            this.isSurveyModalCreated = true;
            this.$createModal = $popup;
        }
    });

    return $.bluethinkinc.popupSurveyModal;
});