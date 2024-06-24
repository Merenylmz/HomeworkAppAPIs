const express = require("express");
const bcrypt = require("bcrypt");
const jwt = require("jsonwebtoken");
const crypto = require("crypto");

const Users = require("../models/Users");
const verifyToken = require("./Common");
const transporter = require("../helpers/sendMail");
const uploads = require("../helpers/imageUpload");

const router = express.Router();


router.post("/register", async(req, res)=>{
    try {
        const isitUser = await Users.findOne({email: req.body.email});
        if (isitUser) {
            return res.send("This user already exists");
        }

        const hashedPassword = await bcrypt.hash(req.body.password, 11);

        const newUser = new Users({
            name: req.body.name,
            email: req.body.email,
            password: hashedPassword
        });

        await newUser.save();

        res.send({user: newUser.email, status: "Succesfuly"});
    } catch (error) {
        console.log(error);
    }
});

router.post("/login", async(req, res)=>{
    try {
        const user = await Users.findOne({email: req.body.email}); 
        if(!user){return res.send("Please valid email");}

        const isThisPassword = await bcrypt.compare(req.body.password, user.password);

        if(!isThisPassword){return res.send("Wrong Password");}
        
        const token = jwt.sign({userId: user._id}, process.env.PrivateKey, {expiresIn: "4h"});


        res.send({token, status: "succesfully"});
    } catch (error) {
        console.log(error);
    }
});

router.post("/forgotpassword", async(req, res)=>{
    try {
        const user = await Users.findOne({email: req.body.email});
        if(!user){return res.send("Please give valid email")}

        const token = crypto.randomBytes(32).toString("hex");
        user.resetToken = token;
        user.resetTokenExpiration = Date.now();
        await user.save();

        await transporter.sendMail({
            from: "myma_ilsender@hotmail.com",
            to: req.body.email,
            subject: "Forgot Password Mail",
            html: `
                <p>If you want change your password, please click a Link</p> <br/>
                <a href="http://localhost:3000/users/newpassword/${token}" class="btn btn-primary btn-sm">Click Here</a>
            
            `
        });

        res.send({status: "succesfully", msg: "Please Check your mail box"});
    } catch (error) {
        console.log(error);
    }
});

router.post("/newpassword/:token", async(req, res)=>{
    try {
        const user = await Users.findOne({resetToken: req.params.token});
        if(!user){return res.send("Please give valid token, if your pass token expiration you must take again");}
        // && user.resetTokenExpiration>Date.now()

        const hashedPassword = await bcrypt.hash(req.body.password, 11);

        user.password = hashedPassword;
        user.resetToken = null;
        user.resetTokenExpiration = null;

        await user.save();

        res.send({status: "OK", msg: "Change Operation Succesfully"});
    } catch (error) {
        console.log(error);
    }
});

router.post("/editphoto", uploads.fields([{name: "profileUrl", maxCount: 1}]) , async(req, res)=>{
    try {
        const {user} = await verifyToken(req.query.token);
        if(!user){return res.send("Please give valid token")}

        const editedUser = await Users.findOne({_id: user._id});
        const imageFile = req.files["profileUrl"][0];
        const imageUrl = `http://localhost:3000/uploads/profilephotos/${imageFile.filename}`;
        editedUser.profilePhoto = imageUrl;

        await editedUser.save();

        res.send(editedUser);
    } catch (error) {
        console.log(error);
    }
});

// router.get("/deneme", async(req, res)=>{
    //     try {
    //         const user = await verifyToken(req.query.token);

    //         res.send(user);
    //     } catch (error) {
    //         console.log(error);
    //     }
    // });

module.exports = router;