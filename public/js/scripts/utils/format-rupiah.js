function formatRupiah(value) {
    value = value.toString().replace(/[^\d,]/g, '');
    const parts = value.split(',');
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    return parts.join(',');
}

function formatIDRNumber(value) {
    if (value === null || value === undefined || value === '') return '0';

    const number = parseFloat(value);

    return number.toLocaleString('id-ID', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
}

function unformatRupiah(value) {
    return value
        .toString()
        .replace(/[^\d,]/g, '')
        .replace(/\./g, '')
        .replace(',', '.');
}
