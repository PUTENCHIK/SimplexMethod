window.addEventListener("DOMContentLoaded", () => {

    const inp_var_amount = document.getElementsByName("variable-amount")[0];
    const inp_limit_amount = document.getElementsByName("limit-amount")[0];
    const form_consts = document.getElementsByName("input-consts")[0];

    inp_var_amount.addEventListener("change", (event) => {
        console.log('vars:', inp_var_amount.value);
        form_consts.submit();
    });

    inp_limit_amount.addEventListener("change", (event) => {
        console.log('limits:', inp_limit_amount.value);
        form_consts.submit();
    });

});