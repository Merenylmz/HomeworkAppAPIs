const multer = require("multer");

const storage = multer.diskStorage({
    destination: (req, file, cb)=>{
        cb(null, "public/uploads/profilePhotos");
    },
    filename: (req, file, cb)=>{
        const fileName = `photos_${Date.now()}_${file.originalname}`;
        cb(null, fileName);
    }
});

const uploads = multer({storage});

module.exports = uploads;