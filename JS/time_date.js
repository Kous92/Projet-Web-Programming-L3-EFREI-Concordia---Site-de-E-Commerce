function time_date(id)
{
        date = new Date;
        year = date.getFullYear();
        month = date.getMonth();
        months_name = new Array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        d = date.getDate();
        day = date.getDay();
        days_name = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        h = date.getHours();

        if (h < 10)
        {
            h = "0"+h;
        }

        m = date.getMinutes();

        if (m < 10)
        {
            m = "0"+m;
        }

        s = date.getSeconds();

        if (s < 10)
        {
            s = "0"+s;
        }

        if ((d == 1) || (d == 21))
        {
            day_type = "st";
        }
        else if ((d == 2) || (d == 22))
        {
            day_type = "nd";
        }
        else if ((d == 3) || (d == 23))
        {
            day_type = "rd";
        }
        else
        {
            day_type = "th";
        }

        result = 'Today we are ' + days_name[day] + ' ' + d + '<sup>' + day_type + '</sup>, ' + months_name[month] + ', ' + year + ' - ' + h + ':' + m + ':' + s;

        document.getElementById(id).innerHTML = result;

        setTimeout('time_date("'+id+'");','1000');

        return true;
}
