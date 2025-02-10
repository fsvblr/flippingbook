(function() {
    let form = document.getElementById('flippingbook_list_form');
    if(form) {
        let statusBoxes = form.querySelectorAll('.status-box'),
            all = document.getElementById('status-boxes-all');
        if(all) {
            all.addEventListener('change', function() {
                let isAllChecked = this.checked;
                statusBoxes.forEach(function (box) {
                    box.checked = isAllChecked;
                });
            });

            statusBoxes.forEach(function (box) {
                box.addEventListener('change', function() {
                    if(!this.checked) {
                        all.checked = false;
                    } else {
                        let c = true, i, e, n;
                        for (i = 0, n = form.elements.length; i < n; i++) {
                            e = form.elements[i];
                            if (e.type === 'checkbox' && e.name !== 'ids_all' && !e.checked) {
                                c = false;
                                break;
                            }
                        }
                        form.elements['ids_all'].checked = c;
                    }
                });
            });
        }
    }
})();
