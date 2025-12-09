'use strict';

function Validasi(form, attr) {
    this.form = form;
    this.attr = attr;
};

Validasi.prototype.validate = function() {
    var $form = document.querySelector(this.form),
        $this = this;
    var $inputs = $form.getElementsByTagName('input'),
        $select = $form.getElementsByTagName('select');

    // Cek Ke absahan Form
    if($form.tagName !== 'FORM' || $form === null || typeof $form === 'undefined') {
        console.log("Parameter Pertama Bukan Form");
        return;
    }

    try{
        for(var i = 0; i < $inputs.length; i++) {
          
            // Cek Invalid Input
            $inputs[i].oninvalid = function(e) {
                var name = (e.target.hasAttribute('data-input')) ? e.target.getAttribute('data-input') : "Form";
                return this.setCustomValidity(name +" "+ $this.attr.required);
                
            };
           $inputs[i].oninput = function(e) {
                return $this.isInvalid(this);
           }   
        }
        
        // Khusus Tag Select
        for(var s = 0; s < $select.length; s++) {

            $select[s].oninvalid = function(e) {
                var name = (e.target.hasAttribute('data-input')) ? e.target.getAttribute('data-input') : "Form";
                return this.setCustomValidity(name +" "+ $this.attr.select);
            }

            $select[s].onchange = function(e) {
                this.setCustomValidity('');
            }
        }
    } catch (e) {
        console.log(e);
    }

    
};

Validasi.prototype.isInvalid = function (input) {
    console.log(input);
    var $this = this;
    if(input.validity.typeMismatch) {
        var type = input.getAttribute('type');
        
        switch(type) {
            case 'email':
                return input.setCustomValidity($this.attr.typeMismatch.email);
                break;
            case 'number':
                return input.setCustomValidity($this.attr.typeMismatch.number);
                break;
            case 'tel':
                return input.setCustomValidity($this.attr.typeMismatch.telephone);
                break;
            case 'url':
                return input.setCustomValidity($this.attr.typeMismatch.url);
                break;
            case 'time':
                return input.setCustomValidity($this.attr.typeMismatch.time);
                break;
            case 'date':
                return input.setCustomValidity($this.attr.typeMismatch.date);
                break;
            
        }

    }
    else if(!input.validity.patternMismatch) {
        input.setCustomValidity($this.attr.patternMismatch);
    }

    return input.setCustomValidity('');
}

