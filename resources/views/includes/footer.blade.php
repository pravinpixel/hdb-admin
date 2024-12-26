        </div>
        <!-- END wrapper -->
        <script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <script src="{{ asset('dark/assets/js/jquery.min.js')}}"></script>
        <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
        <script src="{{ asset('dark/assets/js/bootstrap.min.js')}}"></script>
        <script src="{{ asset('dark/assets/js/detect.js')}}"></script>
        <script src="{{ asset('dark/assets/js/fastclick.js')}}"></script>
        <script src="{{ asset('dark/assets/js/jquery.slimscroll.js')}}"></script>
        <script src="{{ asset('dark/assets/js/jquery.blockUI.js')}}"></script>
        <script src="{{ asset('dark/assets/js/waves.js')}}"></script>
        <script src="{{ asset('dark/assets/js/wow.min.js')}}"></script>
        <script src="{{ asset('dark/assets/js/jquery.nicescroll.js')}}"></script>
        <script src="{{ asset('dark/assets/js/jquery.scrollTo.min.js')}}"></script>

        <script src="{{ asset('dark/assets/js/jquery.app.js')}}"></script>

        <!-- moment js  -->
        <script src="{{ asset('dark/assets/plugins/moment/moment.js')}}"></script>
        
        <!-- counters  -->
        <script src="{{ asset('dark/assets/plugins/waypoints/lib/jquery.waypoints.js')}}"></script>
        <script src="{{ asset('dark/assets/plugins/counterup/jquery.counterup.min.js')}}"></script>
        
        <!-- sweet alert  -->
        <script src="{{ asset('dark/assets/plugins/sweetalert/dist/sweetalert.min.js')}}"></script>
        
        
        <!-- flot Chart -->
        <script src="{{ asset('dark/assets/plugins/flot-chart/jquery.flot.js')}}"></script>
        <script src="{{ asset('dark/assets/plugins/flot-chart/jquery.flot.time.js')}}"></script>
        <script src="{{ asset('dark/assets/plugins/flot-chart/jquery.flot.tooltip.min.js')}}"></script>
        <script src="{{ asset('dark/assets/plugins/flot-chart/jquery.flot.resize.js')}}"></script>
        <script src="{{ asset('dark/assets/plugins/flot-chart/jquery.flot.pie.js')}}"></script>
        <script src="{{ asset('dark/assets/plugins/flot-chart/jquery.flot.selection.js')}}"></script>
        <script src="{{ asset('dark/assets/plugins/flot-chart/jquery.flot.stack.js')}}"></script>
        <script src="{{ asset('dark/assets/plugins/flot-chart/jquery.flot.crosshair.js')}}"></script>
        <script src="{{ asset('dark/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>

        <!-- todos app  -->
        <script src="{{ asset('dark/assets/pages/jquery.todo.js')}}"></script>
        
        <!-- chat app  -->
        <script src="{{ asset('dark/assets/pages/jquery.chat.js')}}"></script>
        <script type="text/javascript" src="{{ asset('dark/assets/plugins/jquery-validation/dist/jquery.validate.min.js') }}" ></script>

        <!-- Datatable  -->
        <script>
            $(document).ready( function () {
                //$('#myTable').DataTable();
            } );
        </script>

        <!-- Tooltip  -->
        <script>
            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            })
        </script>
        
        <!-- dashboard  -->
        <script type="text/javascript">

        var date = new Date();
        var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
        var  week_day = new Date(date.getFullYear(), date.getMonth(), date.getDate() - 6);
            jQuery(document).ready(function($) {
                $('.counter').counterUp({
                    delay: 100,
                    time: 1200
                });
            });

        $(document).on('click', '.delete-modal-popup', function (event) {
            $("#delete-confirmation-modal").modal('show');
            var action = $(this).attr('data-action');
            var csrfToken = $('meta[name=csrf-token]').attr("content");
            var modal = $("#delete-confirmation-modal");
            modal.find('.modal-footer form').prop("action", action);
            modal.find('input[name="_token"]').prop("value", csrfToken);
        });
        
    $('div.alert').not('.alert-important').delay(3000).fadeOut(350);
        function isNumber(evt) {
            var iKeyCode = (evt.which) ? evt.which : evt.keyCode
            if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
                return false;
            return true;
        }

    $(document).ready(function(){
        const token = '{{ csrf_token() }}';
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': token
                }
            });
        
               var start_date = new Date();  
                start_date.setDate(start_date.getDate() - 7);
                $("#start_date").datepicker("setDate",start_date);

                var end_date = new Date();  
                $("#end_date").datepicker("setDate",end_date);


        $('#delete').on('show.bs.modal', function (e) {
            var removedLinkFull = $(e.relatedTarget).data('href');
            var message         = $(e.relatedTarget).data('message');
            var title           = $(e.relatedTarget).data('title');
            var method          = $(e.relatedTarget).data('method');
            $('#title').text(title);
            $('#message').text(message);
            $('#method').val(method);
            $('#remove-form').attr('action', removedLinkFull);
        });

    });
		$(".loader").css('display','block');
        $(document).ajaxStart(function(){ 
            $("body").css('pointer-events' ,'none');
			$(".loader").css('display','block');
			$(".load-bg").css('display','block');
        });
        $(document).ajaxStop(function(){ 
            $("body").css('pointer-events' ,'auto');
			$(".loader").css('display','none');
            $(".load-bg").css('display','none');
        });
        $(window).load(function() {
            $('.load-bg').hide();
        });
    function errorAlert(msg) {
        $('#error-message').html(msg);
        $('#error-modal').modal('show');
        return false;
    }
    function successAlert(msg) {
        $('#success-message').html(msg);
        $('#success-modal').modal('show');
        return true;
    }
    function getNotification(){
        $.ajax({
                url:"{{route('dashboard.get-notification')}}",
                type: "GET",
                success: function(res){
                    $("#header-notification").html(res);
                    $("#header-noti").html($('#notification-html').val());
                }
            });
    }
        function DeleteKeys(myObj, array) {
            for (let index = 0; index < array.length; index++) {
                    delete myObj[array[index]];
            }
            return myObj;
        }
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-dismiss alert after 5 seconds
            setTimeout(function() {
                $('.alert-dismissible').alert('close');
            }, 3000);
        });

       
        </script>

    
    </body>

<!-- Mirrored from moltran.coderthemes.com/dark/ by HTTrack Website Copier/3.x [XR&CO'2010], Tue, 22 Mar 2016 09:17:44 GMT -->