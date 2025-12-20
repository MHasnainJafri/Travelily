// components/Image.js
export default function Image({ src, alt, width, height, ...props }:any) {
  return (
    <img
      src={src}
      alt={alt}
      width={width}
      height={height}
      loading="lazy"
      {...props}
    />
  );
}
