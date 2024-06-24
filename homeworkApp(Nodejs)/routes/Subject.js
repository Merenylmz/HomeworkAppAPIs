const express = require("express");
const Subject = require("../models/Subject");
const Users = require("../models/Users");
const verifyToken = require("./Common");
const router = express.Router();

router.get("/", async(req, res)=>{
    try {
        const subjects = await Subject.find();
        
        res.send(subjects);
    } catch (error) {
        console.log(error);
    }
});

// subject de bulunan homeworksleri getirir
router.get("/:id", async(req, res)=>{
    try {
        const subject = await Subject.findOne({_id: req.params.id});
        res.send(subject.homeworks);
    } catch (error) {
        console.log(error);
    }
});

router.post("/", async(req, res)=>{
    try {
        const user = await verifyToken(req.query.token);
        if(!user){return res.send("Please give valid token");}
        if (!user.isAdmin) {return res.send("No deletion permission");}

        const newSubject = new Subject({
            title: req.body.title
        });

        const status = await newSubject.save();

        res.send(status);
    } catch (error) {
        console.log(error);
    }
});

router.delete("/:id", async(req, res) =>{
    try {
        const user = await verifyToken(req.query.token);
        if(!user){return res.send("Please give valid token");}
        if (!user.isAdmin) {return res.send("No deletion permission");}

        await Subject.findOneAndDelete({_id: req.params.id});

        res.send("Succesfuly");
    } catch (error) {
        console.log(error);
    }
});

router.put("/:id", async(req, res)=>{
    try {
        const user = await verifyToken(req.query.token);
        if(!user){return res.send("Please give valid token");}
        if (!user.isAdmin) {return res.send("No deletion permission");}

        const subject = await Subject.findOneAndUpdate({_id: req.params.id}, {
            title: req.body.title
        });
        await subject.save();

        res.send(subject);
    } catch (error) {
        console.log(error);
    }
}); 

module.exports = router;