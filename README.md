# youtube_streaming_php

# DON'T FORGET TO RENAME TEMPLATE FILE!!!

[Youtube's docs for API request](https://developers.google.com/youtube/v3/live/docs/liveBroadcasts?hl=ru#properties)

* to create a thubnail image (preview) for broadcast, specify `snippet.thumbnails` parameters (width, height, url) if API request
* for specifying delay, set `contentDetails.monitorStream.enableMonitorStream` to `true` and specify delay in ms in `contentDetails.monitorStream.broadcastStreamDelayMs` parameter.