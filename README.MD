
# 📦 Media Module

The `Media` module is responsible for managing file uploads, storage, and media file delivery in the project. It leverages the powerful [Spatie Media Library](https://spatie.be/docs/laravel-medialibrary) package and provides the following features:

* Permanent and temporary file uploads
* Custom storage path support
* File presentation via API resources

---

## 📁 Folder & File Structure

### `app/Models/`

* `Media.php` – Customized Media model extending Spatie's media model

---

### `app/Http/Controllers/`

* `MediaUploadController.php` – Main controller for media file uploads

---

### `app/Http/Requests/`

* `MediaUploadRequest.php` – Validates uploaded files including type, size, etc., according to project requirements

---

### `app/Http/Resources/`

* `MediaResource.php` – Resource used to present finalized media files
* `MediaTempResource.php` – Resource used to present temporary media files

---

### `app/Contracts/`

* `CustomPathGenerator.php` – Defines a custom logic for media storage paths
* `Traits/InteractsWithMedia.php` – Helper trait to easily attach media to models

---

### `app/Enums/`

* `EnumFileType.php` – Enum that defines supported file types (e.g., image, video, document)

---

### `app/Rules/`

* `TempMediaRule.php` – Validation rule for verifying temporary media before final attachment

---

### `app/Jobs/`

* `PruneTempMediaJob.php` – Scheduled job to clean up old temporary files automatically

---

### `app/Services/`

* `MediaService.php` – Core service for uploading, attaching, removing, and managing media file paths

---

## ⚙️ Key Features

* Support for temporary file uploads
* File responses formatted as JSON resources
* Automatic cleanup of temporary files via scheduled jobs
* Uses `CustomPathGenerator` to control storage path logic per file/model/user

---
