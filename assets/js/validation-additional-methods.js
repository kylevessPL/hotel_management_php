$.validator.addMethod("regex", function(value, element, regexp) {
    return this.optional(element) || regexp.test(value);
});

$.validator.addMethod("futuredate", function (value, element) {
    const now = new Date();
    const myDate = new Date(moment(value, 'DD/MM/YYYY').format());
    return this.optional(element) || myDate > now;
});

$.validator.addMethod("afterstartdate", function (value, element, date) {
    const startDate = new Date(moment(date, 'DD/MM/YYYY').format());
    const endDate = new Date(moment(value, 'DD/MM/YYYY').format());
    return this.optional(element) || startDate < endDate || value === "" || date === "";
});

$.validator.addMethod( "creditcard", function(value) {
    if (/[^0-9 \-]+/.test(value)) {
        return false;
    }
    let nCheck = 0,
        nDigit = 0,
        bEven = false,
        n, cDigit;

    value = value.replace(/\D/g, "");

    if (value.length < 12 || value.length > 19) {
        return false;
    }

    for (n = value.length - 1; n >= 0; n--) {
        cDigit = value.charAt(n);
        nDigit = parseInt(cDigit, 10);
        if (bEven) {
            if ((nDigit *= 2) > 9) {
                nDigit -= 9;
            }
        }

        nCheck += nDigit;
        bEven = !bEven;
    }

    return (nCheck % 10) === 0;
}, "Credit card not valid");
