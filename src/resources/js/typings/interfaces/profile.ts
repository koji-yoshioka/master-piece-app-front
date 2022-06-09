export interface UserImage {
  image: File | null
  preview: string | ArrayBuffer | null
  isChanged: boolean
}
