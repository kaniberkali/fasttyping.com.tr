<footer id="footer" class = "container-fluid d-flex justify-content-center align-items-center section text">
    <h4 class = "footerContent display-6 mb-3">Created By</h4>
    <div class = "d-flex justify-content-center mt-3 w-75">
        <div id = "frontendDesign" class = "d-flex flex-column">
            <h5><span class = "footerContent span-500">Emin Ayar</span></h5>
            <div class = "contacts">
            <i class="fa-brands fa-linkedin text"></i>
            <a class = "text" href="https://www.linkedin.com/in/emin-ayar-7549431ab/" target = "_blank">Linkedin</a>
            </div>
            <div class = "contacts">
            <i class="fa-brands fa-github text"></i>
            <a class = "text" href="https://github.com/Em1nayar" target = "_blank">Github</a>
            </div>
        </div>
        <div id = "backendDesign">
        <h5><span class = "footerContent span-500">Ali Kanıberk</span></h5>
            <div class = "contacts">
            <i class="fa-brands fa-linkedin text"></i>
            <a class = "text" href="https://www.linkedin.com/in/ali-kan%C4%B1berk-913505219/" target="_blank">Linkedin</a>
            </div>
            <div class = "contacts">
            <i class="fa-brands fa-github text"></i>
            <a class = "text" href="https://github.com/kaniberkali" target = "_blank">Github</a>
            </div>
        </div>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
<script> 
var pages = <?php echo '["' . implode('", "', $parameters ) . '"]' ?>;
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/rowreorder/1.3.1/js/dataTables.rowReorder.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
<script src="lib/variables.js"></script>
<script src="lib/func.js"></script>
<script src="lib/events.js"></script>
<script src="training.js"></script>
<script async src="https://www.googletagmanager.com/gtag/js?id=G-5XLM59F388"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</body>
</html>