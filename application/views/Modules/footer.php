<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
                <footer style="padding-bottom: 10px; background-color: #fff;">
                    <hr>
                    <p class="pull-right">Desarrollado para Ediciones Católicas - 2018</p>
                </footer>
            </div>
        </div>
<!--        <script>
            $(document).ready(function () {
                $(".cerrarSesion").on("click",function(event){
                    event.preventDefault();
                    $.ajax({
                        url: "< ?= base_url() . 'Login/signOut/'; ?>",
                        type: "POST", 
                        data: {},
                        success:function(){
                            location.href = "< ?= base_url(); ?>";
                        }
                    });
                });
            });
        </script>-->
    </body>
</html>