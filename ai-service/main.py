from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
from typing import List
import pandas as pd
from recommender import recommend_courses

app = FastAPI(title="Online Course Recommender AI Service")


class RecommendationRequest(BaseModel):
    user_id: str
    interest: str


class CourseRecommendation(BaseModel):
    course_id: str
    title: str
    score: float
    description: str
    level: str
    url: str
    platform: str
    skills: str


class RecommendationResponse(BaseModel):
    user_id: str
    recommendations: List[CourseRecommendation]


@app.get("/")
def read_root():
    return {
        "message": "Welcome to AI Recommendation Service"
    }


@app.post("/recommend", response_model=RecommendationResponse)
def get_recommendation(request: RecommendationRequest):

    recs = recommend_courses(
        request.interest
    )

    return {
        "user_id": request.user_id,
        "recommendations": recs
    }


@app.get("/courses/{course_id}")
def get_course_detail(course_id: int):
    from recommender import df
    
    if course_id < 0 or course_id >= len(df):
        raise HTTPException(status_code=404, detail="Course not found")
        
    course = df.iloc[course_id]
    
    return {
        "course_id": str(course_id),
        "title": course["course_title"] if not pd.isna(course["course_title"]) else "",
        "description": course["description"] if "description" in course and not pd.isna(course["description"]) else "",
        "level": course["level"] if "level" in course and not pd.isna(course["level"]) else "",
        "url": course["url"] if "url" in course and not pd.isna(course["url"]) else "",
        "platform": course["platform"] if "platform" in course and not pd.isna(course["platform"]) else "",
        "skills": course["skills"] if "skills" in course and not pd.isna(course["skills"]) else "",
    }
