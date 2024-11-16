#!/usr/bin/perl -wT
# The shebang line tells the system to use the Perl interpreter to execute this script.
# -w enables warnings for better debugging.
# -T enables taint mode for security, preventing untrusted user input from being used directly.

use CGI ':standard'; # Import the CGI module and standard functions (e.g., param, header, start_html).
use CGI::Carp qw(warningsToBrowser fatalsToBrowser); 
# Enables error handling: warnings and fatal errors will be displayed in the browser for easier debugging.

# Print the HTTP header specifying the content type as HTML
print "Content-type: text/html\n\n";

# Start a multiline HTML block using "here-document" syntax (<<'END_HTML').
# The content between <<'END_HTML' and END_HTML is treated as a literal string.

print <<'END_HTML';
<!DOCTYPE html>
<!-- Declares the document as HTML5 -->

<html lang="en">
<!-- Sets the language of the document to English -->

<head>
    <!-- Metadata and settings for the HTML document -->

    <meta charset="UTF-8">
    <!-- Specifies the character encoding as UTF-8 (supports most characters) -->

    <title>My First Perl Program</title>
    <!-- Sets the title of the HTML document (appears on the browser tab) -->

    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <!-- Link to an external Google Font called 'Roboto' for styling text -->

    <style>
        /* Internal CSS for styling the HTML elements */

        body {
            font-family: 'Roboto', sans-serif;
            /* Sets the font of the entire page to 'Roboto', a sans-serif font */
        }

        #message {
            color: blue;
            /* Sets the text color of the message to blue */

            font-size: 48px;
            /* Sets the font size of the message text to 48 pixels */

            text-align: center;
            /* Centers the text horizontally on the page */

            margin-top: 20px;
            /* Adds 20 pixels of space above the message */
        }
    </style>
    <!-- End of internal CSS styling -->
</head>

<body>
    <!-- Main content of the page -->

    <div id="message">This is my first Perl program</div>
    <!-- A centered message displayed on the page with a blue font color -->

</body>

</html>
<!-- End of the HTML document -->
END_HTML
# End of the here-document. This signifies the end of the multiline HTML block.
