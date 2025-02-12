<nav class="navbar navbar-expand-lg navbar-light bg-light navbar-fixed-top">
    <div class="container-fluid">
        <button type="button" id="sidebarCollapse" class="navbar-btn">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span> 
        </button>
        <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-align-justify"></i>
        </button>
        &nbsp
        <!-- Full Screen Button -->
        <button class="btn btn-white d-none d-lg-block" onclick="toggleFullScreen()" data-toggle="tooltip" data-placement="right" title="Fullscreen"><strong><i class="bi bi-arrows-fullscreen" style="font-size: 18px; font-color: #555;"></i></strong></button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="nav navbar-nav ml-auto">
                <li class="nav-item">
                    <a id="len1" class="nav-link hoverable" href="logout.php" style="font-color: #12081B;"><i class="fa-solid fa-right-from-bracket"></i>&nbsp;&nbsp;LOGOUT</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script type="text/javascript">
    $(document).ready(function () {
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
            $(this).toggleClass('active');
        });
    });

    $(function(){
    var str = '#len'; //increment by 1 up to 1-nelemnts
    $(document).ready(function(){
        var i, stop;
        i = 1;
        stop = 4; //num elements
        setInterval(function(){
        if (i > stop){
            return;
        }
        $('#len'+(i++)).toggleClass('bounce');
        }, 500)
    });
    });

    function toggleFullScreen() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
            localStorage.setItem('fullscreen', 'true'); // Store fullscreen state
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
                localStorage.removeItem('fullscreen'); // Remove fullscreen state
            }
        }
    }

    // Check and apply fullscreen on page load
    document.addEventListener('DOMContentLoaded', function () {
        const fullscreenState = localStorage.getItem('fullscreen');
        if (fullscreenState === 'true') {
            document.documentElement.requestFullscreen();
        }
    });

    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();   
    });
</script>