<template>
    <div>
        <div class="mb-1">
            <label class="text-sm font-medium text-gray-700">
                {{ trans.get("general.media") }}
            </label>
        </div>
        <div
            class="inline-block px-3 py-1 mb-2 text-xs text-gray-600 bg-gray-100 rounded"
        >
            <strong>{{ trans.get("general.be_aware") }}:</strong>
            {{ trans.get("inspections.no_faces_on_photos") }}
        </div>
        <label>
            <input
                type="file"
                id="files"
                ref="files"
                v-bind="$attrs"
                multiple
                v-on:change="handleFilesUpload()"
            />
        </label>
        <div
            class="grid w-full grid-cols-3 gap-2 mb-3 sm:gap-4 sm:grid-cols-4"
            v-if="files.length > 0"
        >
            <div v-for="(file, key) in files" :key="key" class="">
                <div
                    class="relative w-full p-2 bg-gray-100 rounded"
                    :style="
                        'padding-top: 75%; background-image: url(\'' +
                            file +
                            '\'); background-size:cover; background-position: center center;'
                    "
                >
                    <!-- <img class="object-cover w-full h-24" :src="file.src" /> -->
                    <div
                        class="absolute top-0 right-0"
                        style="right:3px; top:3px;"
                        v-on:click="removeFile(key)"
                    >
                        <Close class="cursor-pointer"></Close>
                    </div>
                </div>
            </div>
        </div>
        <div class="">
            <button
                v-on:click="addFiles()"
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white transition duration-150 ease-in-out rounded-md ring-2 ring-opacity-0 ring-red-500 hover:ring-opacity-40 bg-primary hover:bg-primary-light focus:outline-none active:bg-primary-dark sm:leading-5"
            >
                {{ trans.get("general.add_media") }}
            </button>
        </div>
    </div>
</template>

<script>
import Close from "@/Elements/Close";

export default {
    components: {
        Close
    },

    data() {
        return {
            files: []
        };
    },

    /*
      Defines the method used by the component
    */
    methods: {
        /*
        Adds a file
      */
        addFiles() {
            this.$refs.files.click();
        },

        //   /*
        //   Submits files to the server
        // */
        //   submitFiles() {
        //       /*
        //     Initialize the form data
        //   */
        //       let formData = new FormData();

        //       /*
        //     Iteate over any file sent over appending the files
        //     to the form data.
        //   */
        //       for (var i = 0; i < this.files.length; i++) {
        //           let file = this.files[i];

        //           formData.append("files[" + i + "]", file);
        //       }

        //       /*
        //     Make the request to the POST /select-files URL
        //   */
        //       axios
        //           .post("/select-files", formData, {
        //               headers: {
        //                   "Content-Type": "multipart/form-data"
        //               }
        //           })
        //           .then(function() {
        //               console.log("SUCCESS!!");
        //           })
        //           .catch(function() {
        //               console.log("FAILURE!!");
        //           });
        //   },

        /*
        Handles the uploading of files
      */
        handleFilesUpload() {
            let uploadedFiles = this.$refs.files.files;

            /*
          Adds the uploaded file to the files array
        */

            for (var i = 0; i < uploadedFiles.length; i++) {
                this.handleFile(uploadedFiles[i]);
            }

            this.$emit("mediaChanged", this.files);
        },

        /*
        Removes a select file the user has uploaded
      */
        removeFile(key) {
            this.files.splice(key, 1);
        },

        handleFile(file) {
            var reader = new FileReader();
            var resizedImage;
            let vm = this;
            reader.onload = function(readerEvent) {
                var image = new Image();
                image.onload = function(imageEvent) {
                    // Resize the image
                    var canvas = document.createElement("canvas"),
                        max_size = 2048,
                        width = image.width,
                        height = image.height;
                    if (width > height) {
                        if (width > max_size) {
                            height *= max_size / width;
                            width = max_size;
                        }
                    } else {
                        if (height > max_size) {
                            width *= max_size / height;
                            height = max_size;
                        }
                    }
                    canvas.width = width;
                    canvas.height = height;
                    canvas
                        .getContext("2d")
                        .drawImage(image, 0, 0, width, height);
                    var dataUrl = canvas.toDataURL("image/jpeg");
                    //var resizedImage = vm.dataURLToBlob(dataUrl);
                    vm.files.push(dataUrl);
                };
                image.src = readerEvent.target.result;
            };

            if (file) {
                reader.readAsDataURL(file);
            }
        }
    }
};
</script>
