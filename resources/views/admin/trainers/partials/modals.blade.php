{{-- Add / Edit Trainer Modal --}}
<div class="modal fade modal-gwb" id="trainerFormModal" tabindex="-1" aria-labelledby="trainerFormModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
        <form class="modal-content" id="trainerForm" novalidate enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="trainerFormModalLabel">Add Trainer</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="trainerId" value="">
                    <input type="hidden" id="remove_profile_image" name="remove_profile_image" value="0">
                    <input type="hidden" id="remove_background_image" name="remove_background_image" value="0">

                    <h6 class="text-orange mb-3">Basic Information</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label" for="first_name">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                            <div class="field-error" data-error="first_name"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="last_name">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                            <div class="field-error" data-error="last_name"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="field-error" data-error="email"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="phone">Phone <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                            <div class="field-error" data-error="phone"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="gender">Gender</label>
                            <select class="form-select" id="gender" name="gender">
                                <option value="">Select</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                            <div class="field-error" data-error="gender"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="dob">Date of Birth</label>
                            <input type="date" class="form-control" id="dob" name="dob">
                            <div class="field-error" data-error="dob"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="joining_date">Joining Date</label>
                            <input type="date" class="form-control" id="joining_date" name="joining_date">
                            <div class="field-error" data-error="joining_date"></div>
                        </div>
                    </div>

                    <h6 class="text-orange mb-3">Professional</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label" for="specialization">Specialization</label>
                            <input type="text" class="form-control" id="specialization" name="specialization" placeholder="Type and press Enter">
                            <div class="form-text text-muted small">Add multiple tags (e.g. Strength, Yoga, HIIT)</div>
                            <div class="field-error" data-error="specialization"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="experience">Experience (Years)</label>
                            <input type="number" class="form-control" id="experience" name="experience" min="0" max="60">
                            <div class="field-error" data-error="experience"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="certifications">Certifications</label>
                            <textarea class="form-control" id="certifications" name="certifications" rows="3" placeholder="Comma-separated or short notes"></textarea>
                            <div class="field-error" data-error="certifications"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="skills">Skills</label>
                            <textarea class="form-control" id="skills" name="skills" rows="3" placeholder="e.g. Powerlifting, HIIT, Nutrition"></textarea>
                            <div class="field-error" data-error="skills"></div>
                        </div>
                    </div>

                    <h6 class="text-orange mb-3">Account</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label" for="password">Password <span class="text-muted small" id="passwordHint">(auto-generated if empty)</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" autocomplete="new-password">
                                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password" title="Show password">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-outline-warning" id="btnGeneratePassword" title="Generate password">
                                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                                </button>
                            </div>
                            <div class="field-error" data-error="password"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="password_confirmation">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
                                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password_confirmation" title="Show password">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                            <div class="field-error" data-error="password_confirmation"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="status">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <div class="field-error" data-error="status"></div>
                        </div>
                    </div>

                    <h6 class="text-orange mb-3">Images</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Profile Image</label>
                            <div class="image-upload-box" data-upload="profile_image" tabindex="0" role="button" aria-label="Upload profile image">
                                <i class="fa-solid fa-cloud-arrow-up mb-2"></i>
                                <div class="small">Click or drag & drop (JPG, PNG, WEBP · max 2MB)</div>
                                <input type="file" class="d-none" id="profile_image" name="profile_image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                            </div>
                            <div class="image-preview-wrap profile" id="profilePreviewWrap">
                                <img src="" alt="Profile preview" id="profilePreview" loading="lazy">
                                <button type="button" class="btn-remove-image" data-remove="profile" title="Remove image"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <div class="field-error" data-error="profile_image"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Cover / Background Image</label>
                            <div class="image-upload-box" data-upload="background_image" tabindex="0" role="button" aria-label="Upload background image">
                                <i class="fa-solid fa-image mb-2"></i>
                                <div class="small">Click or drag & drop (JPG, PNG, WEBP · max 5MB)</div>
                                <input type="file" class="d-none" id="background_image" name="background_image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                            </div>
                            <div class="image-preview-wrap" id="backgroundPreviewWrap">
                                <img src="" alt="Background preview" id="backgroundPreview" loading="lazy">
                                <button type="button" class="btn-remove-image" data-remove="background" title="Remove image"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <div class="field-error" data-error="background_image"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-gwb-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-gwb-primary" id="btnSaveTrainer">
                        <span class="btn-text">Save Trainer</span>
                        <span class="btn-spinner spinner-border spinner-border-sm ms-1" role="status" aria-hidden="true"></span>
                    </button>
                </div>
        </form>
    </div>
</div>

{{-- Credentials Modal --}}
<div class="modal fade modal-gwb" id="credentialsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Trainer Login Credentials</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">A welcome email with these credentials has been sent to the trainer.</p>
                <div class="credentials-box">
                    <div class="mb-2"><span class="text-muted small">Email</span><div class="fw-semibold" id="credEmail"></div></div>
                    <div><span class="text-muted small">Temporary Password</span><div class="fw-semibold" id="credPassword"></div></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-gwb-secondary" id="btnCopyCredentials">
                    <i class="fa-solid fa-copy me-1"></i> Copy Credentials
                </button>
                <button type="button" class="btn-gwb-primary" data-bs-dismiss="modal">Done</button>
            </div>
        </div>
    </div>
</div>
