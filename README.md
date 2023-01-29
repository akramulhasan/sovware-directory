# sovware-directory
This is a simple and clean directory pluign by which user can submit their own services to the listing.

# High level overview of the features:
## REST API integrated
Frontend submission and query in font end both happening with REST api custom endpoint. Ajax pagination also powered by Rest API. Ensure proper security by applying callback_permission


## Admin custom list table added
## Custom Role and Capability extended
After installing, this plugin will create a custom role. User registered by this plugin powered Registration form, user will get this custom role by default.
## Custom Registration login and redirection
After registration user will be able to login and will redirected to the same page from user wanted to submit the listing.
## No post found message with Ajax
## After submit the service will be in under review by admin
By default, after submit a Service, it will be in private mode and once an admin review and publish it only then it will be public.

# How to use?
## After install, admin should create 2 pages by using 2 shortcode.
1. [sov-my-listing] - This is for user front end submission. This page will show a Registration form and after registration will have a link to login. And after login user will redirected to this page and can submit their service. User can edit, delete their own items. Once Service submitted, it need to be approved by an Admin.
2. [sov-listing] - This will show all submitted and approved service items.


