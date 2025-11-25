$(document).ready(function () {
    const today = new Date().toISOString().split('T')[0];
    $('#tanggal').attr('max', today);

    let aktivitasCounter = 1;

    function positionTimepicker(instance, inputElement) {
        setTimeout(function () {
            const calendar = instance.calendarContainer;
            if (calendar) {
                if (calendar.parentElement !== document.body) {
                    document.body.appendChild(calendar);
                }

                const rect = inputElement.getBoundingClientRect();
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                const scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;

                calendar.style.position = 'fixed';
                calendar.style.top = rect.bottom + scrollTop + 5 + 'px';
                calendar.style.left = rect.left + scrollLeft + 'px';
                calendar.style.zIndex = '999999';
                calendar.style.margin = '0';

                const calendarRect = calendar.getBoundingClientRect();
                if (calendarRect.right > window.innerWidth) {
                    calendar.style.left = window.innerWidth - calendarRect.width - 10 + 'px';
                }
                if (calendarRect.bottom > window.innerHeight) {
                    calendar.style.top = rect.top + scrollTop - calendarRect.height - 5 + 'px';
                }
            }
        }, 50);
    }

    function initTimePickerForInput(inputElement, isWaktuAwal = true) {
        if (typeof flatpickr !== 'undefined' && inputElement) {
            if ($(inputElement).data('_flatpickr')) {
                $(inputElement).data('_flatpickr').destroy();
            }

            const instance = flatpickr(inputElement, {
                enableTime: true,
                noCalendar: true,
                dateFormat: 'H:i',
                time_24hr: true,
                minuteIncrement: 15,
                disableMobile: true,
                allowInput: false,
                clickOpens: true,
                static: false,
                appendTo: document.body,
                onOpen: function (selectedDates, dateStr, instance) {
                    positionTimepicker(instance, inputElement);
                },
                onReady: function (selectedDates, dateStr, instance) {
                    const calendar = instance.calendarContainer;
                    if (calendar) {
                        calendar.style.position = 'fixed';
                        calendar.style.zIndex = '999999';
                    }
                },
            });
            return instance;
        }
        return null;
    }

    function initTimePicker() {
        if (typeof flatpickr !== 'undefined') {
            $('.waktu-awal-input, .waktu-akhir-input').each(function () {
                const isWaktuAwal = $(this).hasClass('waktu-awal-input');
                initTimePickerForInput(this, isWaktuAwal);
            });

            $(window).on('scroll resize', function () {
                if ($('.flatpickr-calendar').hasClass('open')) {
                    const activeInput = $('.flatpickr-calendar.open').closest('body').find('input.flatpickr-input:focus');
                    if (activeInput.length) {
                        const instance = activeInput[0]._flatpickr;
                        if (instance) {
                            positionTimepicker(instance, activeInput[0]);
                        }
                    }
                }
            });
        } else {
            // Retry jika flatpickr belum tersedia
            setTimeout(initTimePicker, 100);
        }
    }

    function updateHapusButtons() {
        const itemCount = $('.aktivitas-item').length;
        $('.hapus-aktivitas').each(function () {
            if (itemCount > 1) {
                $(this).removeClass('hidden');
            } else {
                $(this).addClass('hidden');
            }
        });
    }

    function updateAktivitasNumbers() {
        $('.aktivitas-item').each(function (index) {
            $(this)
                .find('.aktivitas-number')
                .text('#' + (index + 1));
        });
    }

    // Tunggu sedikit untuk memastikan DOM sudah ready
    setTimeout(function () {
        initTimePicker();
        updateHapusButtons();
    }, 200);

    $('#tambahAktivitas').on('click', function () {
        const template = $('.aktivitas-item').first().clone();
        const newIndex = aktivitasCounter;

        template.find('.aktivitas-number').text('#' + (newIndex + 1));

        template.find('input[name^="aktivitas[0]"]').each(function () {
            const name = $(this).attr('name');
            const newName = name.replace('[0]', '[' + newIndex + ']');
            $(this).attr('name', newName);
        });

        template.find('textarea[name^="aktivitas[0]"]').each(function () {
            const name = $(this).attr('name');
            const newName = name.replace('[0]', '[' + newIndex + ']');
            $(this).attr('name', newName);
            $(this).val('');
        });

        template.find('input').val('');
        template.find('textarea').val('');

        template.find('.hapus-aktivitas').removeClass('hidden');

        template.find('.time-picker').each(function () {
            if ($(this).data('_flatpickr')) {
                $(this).data('_flatpickr').destroy();
            }
        });

        $('#aktivitasContainer').append(template);

        // Inisialisasi timepicker untuk input baru (dengan delay untuk memastikan DOM sudah ready)
        setTimeout(function () {
            template.find('.waktu-awal-input').each(function () {
                initTimePickerForInput(this, true);
            });
            template.find('.waktu-akhir-input').each(function () {
                initTimePickerForInput(this, false);
            });

            // Update icon lucide
            lucide.createIcons();
        }, 100);

        updateHapusButtons();

        aktivitasCounter++;

        $('html, body').animate(
            {
                scrollTop: template.offset().top - 100,
            },
            300
        );
    });

    $(document).on('click', '.hapus-aktivitas', function () {
        const item = $(this).closest('.aktivitas-item');
        const itemCount = $('.aktivitas-item').length;

        if (itemCount > 1) {
            item.find('.time-picker').each(function () {
                if ($(this).data('_flatpickr')) {
                    $(this).data('_flatpickr').destroy();
                }
            });

            item.fadeOut(300, function () {
                $(this).remove();
                updateAktivitasNumbers();
                updateHapusButtons();
            });
        } else {
            alert('Minimal harus ada 1 aktivitas');
        }
    });

    $('form').on('submit', function (e) {
        let isValid = true;
        let errorMessage = '';

        $('.aktivitas-item').each(function (index) {
            const item = $(this);
            const waktuAwal = item.find('.waktu-awal-input').val();
            const waktuAkhir = item.find('.waktu-akhir-input').val();
            const aktivitas = item.find('textarea').val().trim();

            if (!waktuAwal) {
                isValid = false;
                errorMessage = 'Waktu awal harus diisi untuk aktivitas #' + (index + 1);
                return false;
            }

            if (!waktuAkhir) {
                isValid = false;
                errorMessage = 'Waktu akhir harus diisi untuk aktivitas #' + (index + 1);
                return false;
            }

            // Validasi waktu akhir harus setelah waktu awal
            if (waktuAwal && waktuAkhir && waktuAkhir <= waktuAwal) {
                isValid = false;
                errorMessage = 'Waktu akhir harus setelah waktu awal untuk aktivitas #' + (index + 1);
                return false;
            }

            if (!aktivitas || aktivitas.length < 10) {
                isValid = false;
                errorMessage = 'Deskripsi aktivitas minimal 10 karakter untuk aktivitas #' + (index + 1);
                return false;
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert(errorMessage);
            return false;
        }
    });
});
