<div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    You have <a href="{{ route('websites.edit', $firstWebsiteId) }}" target="_blank">{{ $websitesMissingGoogleDriveCount }}</a> websites to add Google Drive link.
</div>
