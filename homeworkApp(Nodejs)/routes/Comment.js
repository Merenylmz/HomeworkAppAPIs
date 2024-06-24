const express = require("express");
const verifyToken = require("./Common");
const router = express.Router();
const Comments = require("../models/Comments");
const Homework = require("../models/Homework");

router.post("/add", async(req, res)=>{
    try {
        const user = await verifyToken(req.query.token);
        if(!user){return res.send("Please give valid token");}

        const newComment = new Comments({
            name: user.name,
            description: req.body.description,
            userId: user._id,
            homeworkId: req.body.homeworkId
        });
        await newComment.save();

        const homework = await Homework.findOne({_id: req.body.homeworkId});
        homework.comments.push({
            commentId: newComment._id,
            description: req.body.description
        });

        await homework.save();


        res.send({status:"OK", msg: "Comment Added"});
    } catch (error) {
        console.log(error);
    }
});


module.exports = router;