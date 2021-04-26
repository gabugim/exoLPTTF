<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <title>Document</title>
</head>

<body>


    <?php
    // Je vérifie si le formulaire est soumis comme d'habitude
    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        if (isset($_POST['submit']) && $_POST['submit'] == "save") { // Securité en php
            // chemin vers un dossier sur le serveur qui va recevoir les fichiers uploadés (attention ce dossier doit être accessible en écriture)
            $uploadDir = 'uploads/';
            // le nom de fichier sur le serveur est ici généré à partir du nom de fichier sur le poste du client (mais d'autre stratégies de nommage sont possibles)
            $uploadFile = $uploadDir . basename($_FILES['avatar']['name']);
            // Je récupère l'extension du fichier
            $extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            // Les extensions autorisées
            $extensions_ok = ['jpg', 'jpeg', 'png', 'webp'];
            // Le poids max géré par PHP par défaut est de 2M
            $maxFileSize = 1000000;


            // rendre le nom de la photo unique via uniquid


            //basename (uniqid ($_FILES['avatar']['name'], bool $more_entropy = false ) : string)

            // Je sécurise et effectue mes tests

            /****** Si l'extension est autorisée *************/

            $errors = array();

            //start validation
            if (empty($_POST['firstname'])) {
                $errors['firstName1'] = "Your first name cannot be empty";
            }

            if (empty($_POST['lastname'])) {
                $errors['lastName1'] = "Your last name cannot be empty";
            }




            if ((!in_array($extension, $extensions_ok))) {
                $errors['extension1'] = 'Veuillez sélectionner une image de type Jpg ou Jpeg ou Png ou webp!';
            }

            /****** On vérifie si l'image existe et si le poids est autorisé en octets *************/
            if (file_exists($_FILES['avatar']['tmp_name']) && filesize($_FILES['avatar']['tmp_name']) > $maxFileSize) {
                $errors['size'] = "Votre fichier doit faire moins de 1M !";
            }

            /****** Si je n'ai pas d"erreur alors j'upload *************/

            if (empty($errors)) {
                $file_name = $_FILES['avatar']['name'];
                $file_ext = explode('.', $file_name);
                $file_ext = strtolower(end($file_ext));
                $file_new_name = uniqid('', true) . '.' . $file_ext;
                $file_destination = 'uploads/' . $file_new_name;
                move_uploaded_file($_FILES['avatar']['tmp_name'], $file_destination);
                //move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadFile);
            }
        }
        if (isset($_POST['delete']) && $_POST['delete'] == 'remove') {
            unlink($_POST['last_file_upload']);
        }
    }

    var_dump($_POST);
    ?>

    <!-- <form method="post" enctype="multipart/form-data">
        <label for="imageUpload">Upload an profile image</label>
        <input type="file" name="avatar" id="imageUpload" multiple />
        <label for="firstname">Put your name there buddy </label>
        <input type="text" id="firstname" name="firstname">
        <label for="lastname"> put your last name over ther please pal </label>
        <input type="text" id="lastname" name="lastname">
        <button name="send">Send</button>
    </form> -->

    <!--
  This example requires Tailwind CSS v2.0+ 
  
  This example requires some changes to your config:
  
  ```
  // tailwind.config.js
  module.exports = {
    // ...
    plugins: [
      // ...
      require('@tailwindcss/forms'),
    ]
  }
  ```
-->

    <div class=" space-y-1 text-center">
        <h1 class="text-lg font-medium leading-9 text-gray-1200"> Test du framework Tailwind </h1>
    </div>
    <div>
        <div class=" md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Profile</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        This information will be displayed publicly so be careful what you share.
                    </p>
                </div>

                <h1><?= !empty($_POST['firstname'])  ? $_POST['firstname'] : "" ?></h1>
                <p><?= !empty($_POST['lastname'])  ? $_POST['lastname'] : "" ?></p>
                <div class="flex -space-x-2 overflow-hidden ">
                    <img class="inline-block h-40 w-40 rounded-full ring-2 ring-white" src="<?= !empty($file_destination)  ? $file_destination : "" ?>" alt="">
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6">

                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form action="#" method="POST" enctype="multipart/form-data">
                    <div class="shadow sm:rounded-md sm:overflow-hidden">
                        <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                            <div>
                                <label for="firstname" class="block text-sm font-medium text-gray-700">
                                    firstname
                                </label>
                                <p class="text-red-600">
                                    <?php if (isset($errors['firstName1'])) echo $errors['firstName1'];  ?>
                                </p>
                                <div class="mt-1">
                                    <textarea id="firstname" name="firstname" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="homer"></textarea>
                                </div>
                            </div>
                            <div>
                                <label for="lastname" class="block text-sm font-medium text-gray-700">
                                    Lastname
                                </label>
                                <p class="text-red-600">
                                    <?php if (isset($errors['lastName1'])) echo $errors['lastName1'];  ?>
                                </p>
                                <div class=" mt-1">
                                    <textarea id="lastname" name="lastname" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="simpson"></textarea>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Cover photo
                                </label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="avatar" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                <span>Upload a file</span>
                                                <input id="avatar" name="avatar" type="file" class="sr-only" multiple>
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">
                                            PNG, JPG, GIF up to 1MB
                                        </p>
                                        <p class="text-red-600">
                                            <?= isset($errors['extension1']) ? $errors['extension1'] : "" ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <input type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" value="save" name="submit">

                            <input type="hidden" value="<?= isset($file_destination) ? $file_destination : "" ?>" name="last_file_upload">
                            <input type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" value="remove" name="delete">


                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>