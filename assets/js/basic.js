/* jQuery ajax function 
     * post using the comment form & get back response from server & display on page.
*/

$(function () /* anonymous func */ {
    /* capture the form submit event */
    $("#getCloudProvider").submit(function (event) {
        /* stop form from submitting normally */
        event.preventDefault();

        /* setup post function vars */
        var url = $(this).attr('action');
        var postdata = $(this).serialize();

        /* send the data using post and put the results in a div with id="result" */
        /* post(url, postcontent, callback, datatype returned) */

        var request = $.post(
            url,
            postdata,
            formpostcompleted,
            "json"
        ); // end post function            

        function formpostcompleted(data, status) {
            $(".result").show();

            if( data["cloudprovider"] ) {
                $("#resultCP").html("<span class='label label-success'>Found</span> Cloud Provider");
                $list = "<ul class='list-group'>";
                for (len = data["cloudproviderlist"].length, i=0; i<len; ++i) {
                    $list += '<li class="list-group-item cplist"><img class="cpicon" src="' + data['cloudproviderlist'][i]['logo'] + '"/>' + data['cloudproviderlist'][i]['name'] + '</li>';
                }
                $list += "</ul>";
                $("#resultCPlist").html($list);                
                $("#resultCPlist").show();                
            } else {
                $("#resultCP").html("<span class='label label-danger'>Unknown</span> Cloud Provider");                
                $("#resultCPlist").html("");
                $("#resultCPlist").hide();                
            }

            if( data["mx"] ) {
                $("#resultMX").html("<span class='label label-success'>Found</span> DNS MX Records");
                $list = "<ul class='list-group'>";
                for (len = data["mxlist"].length, i=0; i<len; ++i) {
                    $list += "<li class='list-group-item'>" + data["mxlist"][i] + "</li>";
                }
                $list += "</ul>";
                $("#resultMXlist").html($list);                
                $("#resultMXlist").show();                
            } else {
                $("#resultMX").html("<span class='label label-danger'>Unknown</span> DNS MX Records");                
                $("#resultMXlist").html("");
                $("#resultMXlist").hide();                
            }

            if( data["dmarc"] ) {
                $("#resultDMARC").html("<span class='label label-success'>Found</span> DMARC Records");
                $list = "<ul class='list-group'>";
                for (len = data["dmarclist"].length, i=0; i<len; ++i) {
                    $list += "<li class='list-group-item'>" + data["dmarclist"][i] + "</li>";
                }
                $list += "</ul>";
                $("#resultDMARClist").html($list);                
                $("#resultDMARClist").show();                
            } else {
                $("#resultDMARC").html("<span class='label label-danger'>Unknown</span> DMARC Records");                
                $("#resultDMARClist").html("");
                $("#resultDMARClist").hide();                
            }
        }
    }); // end submit function
})