from fastapi import FastAPI
from pydantic import BaseModel
from typing import List
from recommender import recommend_courses

app = FastAPI(title="Online Course Recommender AI Service")


class RecommendationRequest(BaseModel):
    user_id: str
    interest: str


class CourseRecommendation(BaseModel):
    course_id: str
    title: str
    score: float


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