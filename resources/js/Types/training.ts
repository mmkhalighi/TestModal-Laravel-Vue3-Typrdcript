export interface TrainingNodeDraft {
    id: string;
    title: string;
    video: File | null;
    video_url?: string | null;
    children: TrainingNodeDraft[];
}

export interface TrainingNodePayload {
    title: string;
    video: File | null;
    children: TrainingNodePayload[];
}

export interface TrainingFormData {
    name: string;
    nodes: TrainingNodePayload[];
}
export interface TrainingNodeForm {
    id: string;
    title: string;
    video: File | null;
    video_url?: string | null;
    children: TrainingNodeForm[];
}
