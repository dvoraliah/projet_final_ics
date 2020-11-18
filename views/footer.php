<script>
    // Change la race du chien en fonction du chien selectionné
    var selectChien = document.querySelector("#chien");
    selectChien.addEventListener("change", function(){
        let race = selectChien.querySelector("option:checked");
        document.querySelector("#race").value = race.getAttribute("race");
    });
    
</script>
</body>

</html>