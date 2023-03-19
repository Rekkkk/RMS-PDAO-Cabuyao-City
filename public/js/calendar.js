$( function() {

    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

    function dateMonthLimit(inputDate, format) {
        //   the input date
        const date = new Date(inputDate);

        //extract the parts of the date
        const day = date.getDate();
        const month = date.getMonth() + 1;
        const year = date.getFullYear();    

        //replace the month
        format = format.replace("MM", month.toString().padStart(2,"0"));        

        //replace the year
        if (format.indexOf("yyyy") > -1) {
            format = format.replace("yyyy", year.toString());
        } else if (format.indexOf("yy") > -1) {
            format = format.replace("yy", year.toString().substr(2,2));
        }

        //replace the day
        format = format.replace("dd", day.toString().padStart(2,"0"));

        return format;
    }

    var dates = [];

    // console.log(dates);

    var totalAppointment;

    for(var i = 0; i < appointmentLimit.length; i++){

        var dateFormatNumOfappointmentLimit = new Date(appointmentLimit[i].appointment_month)

        for(var days = 1; days < 32; days++){

            totalAppointment = 0;

            for(var temp = 0; temp < appointmentDate.length; temp++){

                var dateFormatNumOfAppointments = new Date(appointmentDate[temp].appointment_date)

                if(dateFormatNumOfAppointments.getDate() === days){

                    if((dateFormatNumOfAppointments.getMonth() + "-"+ dateFormatNumOfAppointments.getFullYear()) 
                        == dateFormatNumOfappointmentLimit.getMonth() + "-" + dateFormatNumOfappointmentLimit.getFullYear()){
                        totalAppointment += 1;        
                    }

                    if(totalAppointment === appointmentLimit[i].limits ){
                        dates.push(dateMonthLimit(dateFormatNumOfAppointments, "MM-dd-yyyy"))
                        break;
                    }
                }
            }
        }       
    }

    if(disableDates.length != 0){
        for(let i = 0; i < disableDates.length; i++){
            var dateFormat = new Date(disableDates[i].date);
            if(dateFormat.getDate() < 10){
                if(dateFormat.getMonth()  < 9)
                    dates.push("0"+(dateFormat.getMonth() + 1) + "-0" + dateFormat.getDate() + "-" + dateFormat.getFullYear())
                else
                    dates.push((dateFormat.getMonth() + 1) + "-0" + dateFormat.getDate() + "-" + dateFormat.getFullYear())
            }
            else{
                if(dateFormat.getMonth() < 9)
                    dates.push("0" + (dateFormat.getMonth() + 1) + "-" + dateFormat.getDate() + "-" + dateFormat.getFullYear())
                else
                    dates.push((dateFormat.getMonth()+1) + "-" + dateFormat.getDate() + "-" + dateFormat.getFullYear())
            }
        }
    }

    function DisableDates(date) {
        var noWeekend = $.datepicker.noWeekends(date);
       
        if (noWeekend[0]) {
            var string = jQuery.datepicker.formatDate('mm-dd-yy', date);
            return [dates.indexOf(string) == -1];
        } 
        else {
            return noWeekend;
        }
    }

    var today = new Date();
    var lastDayOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);
    var numberOfMonthsShow;

    if(lastDayOfMonth.getDate() <= today.getDate()+14){
        numberOfMonthsShow = 2;
    }else{
        numberOfMonthsShow = 1;
    }

    $("#datepicker").datepicker({
        minDate:  new Date(today.getFullYear(), today.getMonth() + numberOfMonthsShow),
        showButtonPanel: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm',
        stepMonths: true,

        onChangeMonthYear: function (year, month, inst) {
            $(this).val($.datepicker.formatDate('yy-mm', new Date(year, month - 1, 1)));
        },
        onClose: function(dateText, inst) {
            var month = $(".ui-datepicker-month :selected").val();
            var year = $(".ui-datepicker-year :selected").val();
            $(this).val($.datepicker.formatDate('yy-mm', new Date(year, month, 1)));
        }
    }).focus(function () {
        $(".ui-datepicker-calendar").hide();
    })

    $("#datepicker-appointment").datepicker({
        currentText: 'Today',
        beforeShowDay: DisableDates,
        minDate:  new Date(today.getFullYear(), today.getMonth() + numberOfMonthsShow),
        numberOfMonths: numberOfMonthsShow,
        minDate: 1,
        gotoCurrent: true,
        changeMonth: false,
        changeYear: false,
        stepMonths: 0,
        startDate: "-0m",
        endDate: "+1y",  
        dateFormat: 'yy-mm-dd',
    });  

    $("#datepicker-disable-date").datepicker({
        currentText: 'Today',
        beforeShowDay: DisableDates,
        minDate:  new Date(today.getFullYear(), today.getMonth() + numberOfMonthsShow),
        numberOfMonths: numberOfMonthsShow,
        gotoCurrent: true,
        changeMonth: true,
        changeYear: true,
        startDate: "-0m",
        endDate: "+1y",  
        dateFormat: 'yy-mm-dd',

    });  

    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
    var yyyy = today.getFullYear();
    if(dd<10){
            dd='0'+dd
        } 
        if(mm<10){
            mm='0'+mm
        } 

    today = yyyy+'-'+mm+'-'+dd;
    document.getElementById("birthday-applicant").setAttribute("max", today);

    $("img").click( function() {
        this.requestFullscreen();
   
    });
});
