#!/usr/bin/perl -wT

use CGI ':standard';
use CGI::Carp qw(warningsToBrowser fatalsToBrowser);
use File::Basename;
use Digest::MD5 qw(md5_hex);

my $query = CGI->new;
print $query->header(-type => 'text/html');

my $first_name   = param('first_name')   || '';
my $last_name    = param('last_name')    || '';
my $street       = param('street')       || '';
my $city         = param('city')         || '';
my $postal_code  = param('postal_code')  || '';
my $province     = param('province')     || '';
my $phone        = param('phone')        || '';
my $email        = param('email')        || '';
my $photo        = upload('photo');

my %errors;

if ($phone !~ /^\d{10}$/) {
    $errors{'phone'} = "Phone number must be exactly 10 digits.";
}

if ($postal_code !~ /^[A-Za-z]\d[A-Za-z] \d[A-Za-z]\d$/) {
    $errors{'postal_code'} = "Postal code must be in the format L0L 0L0.";
}

if ($email !~ /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/) {
    $errors{'email'} = "Email address is invalid.";
}

if ($photo) {
    my $photo_info = uploadInfo($photo);
    my $photo_mime_type = $photo_info->{'Content-Type'} || '';

    if ($photo_mime_type !~ /^image\/(jpeg|png|gif)$/) {
        $errors{'photo'} = "Uploaded file must be a valid image (JPEG, PNG, or GIF).";
    }
} else {
    $errors{'photo'} = "No photo uploaded.";
}

if (%errors) {
    print <<'END_HTML';
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Registration Errors</title>
<style>
body { font-family: Arial, sans-serif; background-color: #ffe6e6; }
.error { color: red; }
.container { width: 600px; margin: 0 auto; }
</style>
</head>
<body>
<div class="container">
<h1>Errors in your submission</h1>
<ul>
END_HTML

    foreach my $field (keys %errors) {
        print "<li class=\"error\">$errors{$field}</li>\n";
    }

    print <<'END_HTML';
</ul>
<p><a href="../Perl_CGI_Programming/lab07b.html">Return to the form</a></p>
</div>
</body>
</html>
END_HTML
    exit;
}

my $upload_dir = "../uploads";
unless (-d $upload_dir) {
    mkdir $upload_dir or die "Cannot create upload directory: $!";
}

my $filename = basename(param('photo'));
$filename =~ s/[^A-Za-z0-9_.-]//g;
my $unique_string   = time . rand();
my $unique_filename = md5_hex($unique_string) . "_" . $filename;
my $filepath        = "$upload_dir/$unique_filename";

my $upload_filehandle = upload('photo');
open(my $upload_fh, '>', $filepath) or die "Cannot open file '$filepath' for writing: $!";
binmode $upload_fh;

while (my $chunk = <$upload_filehandle>) {
    print $upload_fh $chunk;
}
close $upload_fh;

foreach my $var ($first_name, $last_name, $street, $city, $postal_code, $province, $phone, $email) {
    $var = escapeHTML($var);
}

print <<END_HTML;
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Registration Successful</title>
<style>
    body {
        font-family: 'Arial', sans-serif;
        background: linear-gradient(to right, #00c6ff, #0072ff);
        color: #fff;
        margin: 0;
        padding: 0;
    }
    .container {
        width: 90%;
        max-width: 800px;
        margin: 40px auto;
        background-color: rgba(255, 255, 255, 0.9);
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
    }
    h1 {
        text-align: center;
        font-size: 2em;
        color: #0072ff;
        margin-bottom: 20px;
    }
    .user-info {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        margin-top: 20px;
    }
    .info-block {
        flex: 0 1 45%;
        background-color: #f0f8ff;
        color: #0072ff;
        padding: 15px;
        margin: 10px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    .info-block label {
        font-weight: bold;
        color: #333;
    }
    .info-block p {
        margin: 5px 0;
    }
    .photo-container {
        text-align: center;
        margin-top: 30px;
        color: #0072ff;
    }
    .photo-container h2 {
        font-size: 1.5em;
        margin-bottom: 15px;
        color: #0072ff;
    }
    .photo-container img {
        max-width: 100%;
        height: auto;
        border: 3px solid #0072ff;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        background-color: #e6f7ff;
    }
    @media (max-width: 600px) {
        .info-block {
            flex: 0 1 100%;
        }
    }
</style>
</head>
<body>
<div class="container">
    <h1>Registration Successful!</h1>
    <div class="user-info">
        <div class="info-block"><label>First Name:</label><p>$first_name</p></div>
        <div class="info-block"><label>Last Name:</label><p>$last_name</p></div>
        <div class="info-block"><label>Street:</label><p>$street</p></div>
        <div class="info-block"><label>City:</label><p>$city</p></div>
        <div class="info-block"><label>Postal Code:</label><p>$postal_code</p></div>
        <div class="info-block"><label>Province:</label><p>$province</p></div>
        <div class="info-block"><label>Phone:</label><p>$phone</p></div>
        <div class="info-block"><label>Email:</label><p>$email</p></div>
    </div>
    <div class="photo-container">
        <h2>Your Uploaded Photo:</h2>
        <img src="../uploads/$unique_filename" alt="Uploaded Photo">
    </div>
</div>
</body>
</html>
END_HTML

exit;
