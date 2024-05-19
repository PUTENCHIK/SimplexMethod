window.addEventListener("DOMContentLoaded", () => {

    const inp_var_amount = document.getElementsByName("variable-amount")[0];

    inp_var_amount.addEventListener("change", (event) => {
        console.log(inp_var_amount.value);
    });

});