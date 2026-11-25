<script>
function maskPhone(input) {
    var digits = input.value.replace(/\D/g, '');
    var intl = digits.startsWith('55') && (input.value.trim().startsWith('+') || digits.length > 11);

    if (intl) {
        digits = digits.slice(2);
    }

    var ddd = digits.slice(0, 2);
    var number = digits.slice(2, 11);
    var split = number.length > 8 ? 5 : 4;

    input.value = (intl ? '+55 ' : '') +
        (ddd ? '(' + ddd + (number ? ') ' : '') : '') +
        number.slice(0, split) +
        (number.length > split ? '-' + number.slice(split) : '');
}

var phoneInput = document.getElementById('phone');

function handlePhoneInput() {
    maskPhone(phoneInput);
}

if (phoneInput) {
    phoneInput.addEventListener('input', handlePhoneInput);
    maskPhone(phoneInput);
}

var contactForm = document.querySelector('main form');

function validateForm(form) {
    form.classList.add('was-submitted');

    var fields = form.querySelectorAll('.field-input[required]');

    for (var i = 0; i < fields.length; i++) {
        if (fields[i].value.trim() === '') {
            fields[i].focus();
            return false;
        }
    }

    return true;
}

function handleSubmit(event) {
    if (!validateForm(contactForm)) {
        event.preventDefault();
    }
}

if (contactForm) {
    contactForm.addEventListener('submit', handleSubmit);
}
</script>
