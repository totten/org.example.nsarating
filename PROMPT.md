NSA Security Rating Integration

When a do-gooder in remote China has an interaction with a small non-profit,
we want to ask the NSA to rank how well the interaction supports the
national security interests of the United States.  Happily, they provide a
web-service to do this, e.g.

  POST http://think.hm/secrate.php?activity_type=123&long=12.34&lat=56.78
  ==> {"is_error": 0, "color":"green"}

Unfortunately, the service requires geographical coordinates, which is not
normally available.

----------

[x] Add custom data field: Geolocation
[x] Add custom data field: Security rating
[x] Tap into "New Activity" page
[x]   - As soon as you open "New Activity", lookup location
[x]   - As soon as you save the new activity, call remote service and set "Rating"
[x] Publish to Github
