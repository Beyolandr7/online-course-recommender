import pickle
from sklearn.metrics.pairwise import cosine_similarity

# Load model sekali saja
with open("model_experiment_dropped.pkl", "rb") as f:
    model = pickle.load(f)

vectorizer = model["vectorizer"]
tfidf_matrix = model["tfidf_matrix"]
data = model["data"]


def recommend_courses(user_input, top_n=5):
    # Ubah input user jadi vector
    user_vec = vectorizer.transform([user_input])

    # Hitung similarity
    similarity = cosine_similarity(user_vec, tfidf_matrix)

    # Ambil index terbaik
    top_indices = similarity[0].argsort()[-top_n:][::-1]

    results = []
    for idx in top_indices:
        course = data.iloc[idx]
        results.append({
            "course_id": str(idx),
            "title": course["course_title"],
            "score": float(similarity[0][idx])
        })

    return results