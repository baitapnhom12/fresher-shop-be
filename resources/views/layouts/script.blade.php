    <!-- jQuery -->
    <script src="/admin-layout/plugins/jquery/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <!-- Bootstrap -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="/admin-layout/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE -->
    <script src="/admin-layout/dist/js/adminlte.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
        integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
    <!-- OPTIONAL SCRIPTS -->
    <script src="/admin-layout/plugins/chart.js/Chart.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="/admin-layout/dist/js/demo.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="/admin-layout/dist/js/pages/dashboard3.js"></script>
    <script src="/admin-layout/dist/js/adminlte.min.js"></script>
    <script>
        function confirmAction() {
            if (!confirm("Are you sure this action?"))
                event.preventDefault();
        }
    </script>

    <script>
        document.getElementById('logout-link').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('logout-form').submit();
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#select-form').select2();
        });
    </script>

    <!-- DataTables  & Plugins -->
    <script src="/admin-layout/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/admin-layout/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="/admin-layout/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/admin-layout/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="/admin-layout/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="/admin-layout/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="/admin-layout/plugins/jszip/jszip.min.js"></script>
    <script src="/admin-layout/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="/admin-layout/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="/admin-layout/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="/admin-layout/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="/admin-layout/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <script src="/admin-layout/plugins/select2/js/select2.full.min.js"></script>
    <script src="/admin-layout/plugins/moment/moment.min.js"></script>
    <script src="/admin-layout/plugins/inputmask/jquery.inputmask.min.js"></script>
    <script src="/admin-layout/plugins/dropzone/min/dropzone.min.js"></script>
    <script src="/admin-layout/plugins/summernote/summernote-bs4.min.js"></script>
    <script src="/admin-layout/plugins/codemirror/codemirror.js"></script>
    <script src="/admin-layout/plugins/codemirror/mode/css/css.js"></script>
    <script src="/admin-layout/plugins/codemirror/mode/xml/xml.js"></script>
    <script src="/admin-layout/plugins/codemirror/mode/htmlmixed/htmlmixed.js"></script>
    <script src="/admin-layout/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
    <script src="/admin-layout/plugins/daterangepicker/daterangepicker.js"></script>
    <script src="/admin-layout/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
    <script src="/admin-layout/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="/admin-layout/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
    <script src="/admin-layout/plugins/bs-stepper/js/bs-stepper.min.js"></script>
    <script src="/admin-layout/plugins/jquery-ui/jquery-ui.min.js"></script>
    <script src="/admin-layout/plugins/sparklines/sparkline.js"></script>
    <script src="/admin-layout/plugins/jquery-knob/jquery.knob.min.js"></script>
    <script src="/admin-layout/dist/js/pages/dashboard.js"></script>
    <script src="/admin-layout/plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="/admin-layout/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <!-- overlayScrollbars -->
    <script src="/admin-layout/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <script>
        $(function() {
            // Summernote
            $('#summernote').summernote()

            // CodeMirror
            CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
                mode: "htmlmixed",
                theme: "monokai"
            });
        })
    </script>

    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })

            //Datemask dd/mm/yyyy
            $('#datemask').inputmask('dd/mm/yyyy', {
                'placeholder': 'dd/mm/yyyy'
            })
            //Datemask2 mm/dd/yyyy
            $('#datemask2').inputmask('mm/dd/yyyy', {
                'placeholder': 'mm/dd/yyyy'
            })
            //Money Euro
            $('[data-mask]').inputmask()

            //Date picker
            $('#reservationdate').datetimepicker({
                format: 'L'
            });

            //Date and time picker
            $('#reservationdatetime').datetimepicker({
                icons: {
                    time: 'far fa-clock'
                },
                format: 'MM/DD/YYYY HH:mm:ss'
            });

            //Date range picker
            $('#reservation').daterangepicker()
            //Date range picker with time picker
            $('#reservationtime').daterangepicker({
                timePicker: true,
                timePickerIncrement: 30,
                locale: {
                    format: 'MM/DD/YYYY hh:mm:ss'
                }
            })
            //Date range as a button
            $('#daterange-btn').daterangepicker({
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                            'month').endOf('month')]
                    },
                    startDate: moment().subtract(29, 'days'),
                    endDate: moment()
                },
                function(start, end) {
                    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format(
                        'MMMM D, YYYY'))
                }
            )

            //Timepicker
            $('#timepicker').datetimepicker({
                format: 'LT'
            })

            //Bootstrap Duallistbox
            $('.duallistbox').bootstrapDualListbox()

            //Colorpicker
            $('.my-colorpicker1').colorpicker()
            //color picker with addon
            $('.my-colorpicker2').colorpicker()

            $('.my-colorpicker2').on('colorpickerChange', function(event) {
                $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
            })

            $("input[data-bootstrap-switch]").each(function() {
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            })

        })
        // BS-Stepper Init
        document.addEventListener('DOMContentLoaded', function() {
            window.stepper = new Stepper(document.querySelector('.bs-stepper'))
        })
    </script>
