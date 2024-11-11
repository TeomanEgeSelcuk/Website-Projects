#!/usr/bin/perl -wT
# The shebang line tells the system to use the Perl interpreter to run this script.
# -w enables warnings for debugging purposes.
# -T enables taint mode for extra security, preventing unsafe data from being used.

use CGI ':standard'; # Import the CGI module, including standard functions like `param`, `header`, and `upload`.
use CGI::Carp qw(warningsToBrowser fatalsToBrowser); # Configure error handling to display warnings and fatal errors in the browser.

use File::Basename; # Import module for handling file path operations (e.g., extracting filenames).
use Digest::MD5 qw(md5_hex); # Import the MD5 module to generate a unique hash string.

# Create a new CGI object to handle HTTP request and response
my $query = CGI->new;

# Print the HTTP header, specifying the content type as HTML
print $query->header(-type => 'text/html');

# Read input parameters from the HTML form, using `param()` function to fetch values.
# If a parameter is missing, use an empty string as the default value.
my $first_name   = param('first_name')   || ''; # Get 'first_name' or set to an empty string if not provided.
my $last_name    = param('last_name')    || ''; # Get 'last_name' or set to an empty string if not provided.
my $street       = param('street')       || ''; # Get 'street' or set to an empty string if not provided.
my $city         = param('city')         || ''; # Get 'city' or set to an empty string if not provided.
my $postal_code  = param('postal_code')  || ''; # Get 'postal_code' or set to an empty string if not provided.
my $province     = param('province')     || ''; # Get 'province' or set to an empty string if not provided.
my $phone        = param('phone')        || ''; # Get 'phone' or set to an empty string if not provided.
my $email        = param('email')        || ''; # Get 'email' or set to an empty string if not provided.
my $photo        = upload('photo');      # Retrieve the uploaded file with the key 'photo'.

# Initialize an empty hash to store validation error messages
my %errors;

# Validate the phone number: It should contain exactly 10 digits.
if ($phone !~ /^\d{10}$/) {
    # Regular expression `^\d{10}$` checks for exactly 10 digits (0-9).
    $errors{'phone'} = "Phone number must be exactly 10 digits.";
}

# Validate the postal code: It should match the Canadian postal code format (e.g., A1A 1A1).
if ($postal_code !~ /^[A-Za-z]\d[A-Za-z] \d[A-Za-z]\d$/) {
    # Regular expression checks for a letter-digit-letter space digit-letter-digit pattern.
    $errors{'postal_code'} = "Postal code must be in the format L0L 0L0.";
}

# Validate the email address using a basic pattern
if ($email !~ /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/) {
    # Regular expression checks for a typical email structure (e.g., user@example.com).
    $errors{'email'} = "Email address is invalid.";
}

# Check if a photo file was uploaded
if ($photo) {
    my $photo_info = uploadInfo($photo); # Get metadata about the uploaded file.
    my $photo_mime_type = $photo_info->{'Content-Type'} || ''; # Extract the MIME type (e.g., image/jpeg).

    # Validate that the uploaded file is an image (JPEG, PNG, or GIF).
    if ($photo_mime_type !~ /^image\/(jpeg|png|gif)$/) {
        # MIME type must start with "image/" and be followed by "jpeg", "png", or "gif".
        $errors{'photo'} = "Uploaded file must be a valid image (JPEG, PNG, or GIF).";
    }
} else {
    # If no file was uploaded, add an error message.
    $errors{'photo'} = "No photo uploaded.";
}

# Check if there are any errors by examining the %errors hash.
if (%errors) {
    # If the hash is not empty, generate an HTML page to display the errors.

    print <<'END_HTML';
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Registration Errors</title>
<style>
body { font-family: Arial, sans-serif; }
.error { color: red; }
.container { width: 600px; margin: 0 auto; }
</style>
</head>
<body>
<div class="container">
<h1>Errors in your submission</h1>
<ul>
END_HTML

    # Loop through each key-value pair in the %errors hash and display the error messages.
    foreach my $field (keys %errors) {
        print "<li class=\"error\">$errors{$field}</li>\n"; # Display each error as a list item.
    }

    # End the HTML error page with a link back to the form
    print <<'END_HTML';
</ul>
<p><a href="../Perl_CGI_Programming/lab07b.html">Return to the form</a></p>
</div>
</body>
</html>
END_HTML

    # Exit the script after displaying the errors.
    exit;
}

# If there are no errors, proceed with processing the uploaded photo.
my $upload_dir = "../uploads"; # Define the directory to save the uploaded files.

# Check if the upload directory exists; if not, create it.
unless (-d $upload_dir) {
    mkdir $upload_dir or die "Cannot create upload directory: $!"; # Create directory or display an error message.
}

# Get the original filename and sanitize it by removing special characters.
my $filename = basename(param('photo'));
$filename =~ s/[^A-Za-z0-9_.-]//g; # Keep only letters, digits, underscores, dots, and dashes.

# Generate a unique filename using a combination of current time and a random number, hashed with MD5.
my $unique_string   = time . rand(); # Create a unique string using time and a random value.
my $unique_filename = md5_hex($unique_string) . "_" . $filename; # Hash the string and append the sanitized filename.
my $filepath        = "$upload_dir/$unique_filename"; # Define the complete file path.

# Open a file handle to write the uploaded file to the server.
my $upload_filehandle = upload('photo');
open(my $upload_fh, '>', $filepath) or die "Cannot open file '$filepath' for writing: $!";
binmode $upload_fh; # Set binary mode for the file handle (important for non-text files).

# Read the uploaded file in chunks and write it to the server.
while (my $chunk = <$upload_filehandle>) {
    print $upload_fh $chunk;
}
close $upload_fh; # Close the file handle after writing.

# Escape special HTML characters in user input to prevent XSS attacks.
foreach my $var ($first_name, $last_name, $street, $city, $postal_code, $province, $phone, $email) {
    $var = escapeHTML($var);
}

# Print a confirmation page with the user's submitted data and the uploaded photo.
print <<END_HTML;
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Registration Successful</title>
<style>
body { font-family: Arial, sans-serif; }
.container { width: 600px; margin: 0 auto; }
h1 { text-align: center; }
.photo { max-width: 200px; height: auto; display: block; margin-top: 10px; }
</style>
</head>
<body>
<div class="container">
<h1>Registration Successful</h1>
<p><strong>First Name:</strong> $first_name</p>
<p><strong>Last Name:</strong> $last_name</p>
<p><strong>Street:</strong> $street</p>
<p><strong>City:</strong> $city</p>
<p><strong>Postal Code:</strong> $postal_code</p>
<p><strong>Province:</strong> $province</p>
<p><strong>Phone:</strong> $phone</p>
<p><strong>Email:</strong> $email</p>
<p><strong>Photo:</strong><br>
<img src="../uploads/$unique_filename" alt="Photo" class="photo"></p>
</div>
</body>
</html>
END_HTML
